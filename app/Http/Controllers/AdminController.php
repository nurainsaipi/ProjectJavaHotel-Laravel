<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ── Auth ──

    public function loginForm()
    {
        if (session('admin_auth')) {
            return redirect()->route('admin.reservations');
        }
        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('admin.login', compact('availRooms', 'facilities'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_auth' => true, 'admin_name' => $admin->username]);
            return redirect()->route('admin.reservations');
        }

        return redirect()->route('login')->with('error', 'Kredensial tidak valid');
    }

    public function logout()
    {
        session()->forget(['admin_auth', 'admin_name']);
        return redirect()->route('home');
    }

    // ── Reservations ──

    public function reservations()
    {
        $baseQuery = Booking::join('customers', 'bookings.customer_id', '=', 'customers.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                'bookings.*',
                'bookings.payment_status as payment_status',
                'bookings.payment_proof as payment_proof',
                'customers.name as guest',
                'customers.phone',
                'customers.nik',
                'rooms.number as room_no',
                'rooms.type as room_type'
            );

        $bookings = (clone $baseQuery)
            ->whereIn('bookings.payment_status', ['Pending', 'Reviewing', 'Paid'])
            ->orderByDesc('bookings.created_at')
            ->get();

        $history = (clone $baseQuery)
            ->where('bookings.payment_status', 'Checked Out')
            ->orderByDesc('bookings.updated_at')
            ->get();

        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('admin.reservations', compact('bookings', 'history', 'availRooms', 'facilities'));
    }

    public function pay(Request $request)
    {
        $request->validate(['id' => 'required|exists:bookings,id']);
        Booking::find($request->id)->update([
            'payment_status' => 'Paid',
            'payment_method' => $request->method ?? 'Transfer Bank',
            'payment_note'   => $request->note ?? '',
        ]);
        return redirect()->route('admin.reservations')->with('paid', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function rejectProof(Request $request)
    {
        $request->validate(['id' => 'required|exists:bookings,id']);
        Booking::find($request->id)->update([
            'payment_status' => 'Pending',
            'payment_proof'  => null,
            'payment_method' => null,
        ]);
        return redirect()->route('admin.reservations')->with('error', 'Bukti pembayaran ditolak. Tamu perlu upload ulang.');
    }

    public function checkout(Request $request)
    {
        $request->validate(['id' => 'required|exists:bookings,id']);
        $booking = Booking::find($request->id);
        $booking->update(['payment_status' => 'Checked Out']);
        Room::find($booking->room_id)->update(['availability' => 1, 'status' => 'Dirty']);
        return redirect()->route('admin.reservations')->with('out', 'Tamu berhasil checkout!');
    }

    // ── Room Status ──

    public function rooms()
    {
        $rooms = Room::orderBy('number')->get();
        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('admin.rooms', compact('rooms', 'availRooms', 'facilities'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'status'  => 'required|in:Clean,Dirty,Inspected',
        ]);
        Room::find($request->room_id)->update(['status' => $request->status]);
        return redirect()->route('admin.rooms')->with('updated', 'Status kamar diperbarui!');
    }

    // ── Reports ──

    public function reports()
    {
        $daily   = Booking::whereIn('payment_status', ['Paid', 'Checked Out'])
                          ->whereDate('created_at', today())->sum('total_price');
        $weekly  = Booking::whereIn('payment_status', ['Paid', 'Checked Out'])
                          ->where('created_at', '>=', now()->subDays(7))->sum('total_price');
        $monthly = Booking::whereIn('payment_status', ['Paid', 'Checked Out'])
                          ->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)->sum('total_price');
        $yearly  = Booking::whereIn('payment_status', ['Paid', 'Checked Out'])
                          ->whereYear('created_at', now()->year)->sum('total_price');

        $recentBookings = Booking::join('customers', 'bookings.customer_id', '=', 'customers.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereIn('bookings.payment_status', ['Paid', 'Checked Out'])
            ->orderByDesc('bookings.created_at')
            ->limit(8)
            ->select('bookings.*', 'customers.name as guest', 'rooms.number as room_no', 'rooms.type as room_type')
            ->get();

        $max = max($daily, $weekly, $monthly, $yearly, 1);

        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
    return view('admin.reports', compact('daily', 'weekly', 'monthly', 'yearly', 'recentBookings', 'max', 'availRooms', 'facilities'));
    }
}