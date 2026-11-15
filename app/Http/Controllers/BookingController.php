<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Facility;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik'           => 'required|string|max:20',
            'guest_name'    => 'required|string|max:120',
            'phone'         => 'required|string|max:20',
            'email'         => 'required|email|max:120',
            'room_id'       => 'required|exists:rooms,id',
            'checkin_date'  => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
        ]);

        $customer = Customer::updateOrCreate(
            ['nik' => $request->nik],
            [
                'name'  => $request->guest_name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]
        );

        $room  = Room::findOrFail($request->room_id);
        $ci    = Carbon::parse($request->checkin_date);
        $co    = Carbon::parse($request->checkout_date);
        $days  = max(1, $ci->diffInDays($co));
        $total = $room->price * $days;

        $selectedFacilities = null;
        if ($request->has('facilities')) {
            $facIds   = array_map('intval', $request->facilities);
            $facTotal = Facility::whereIn('id', $facIds)->sum('price');
            $total   += $facTotal * $days;
            $selectedFacilities = json_encode($facIds);
        }

        Booking::create([
            'room_id'             => $room->id,
            'customer_id'         => $customer->id,
            'checkin_date'        => $request->checkin_date,
            'checkout_date'       => $request->checkout_date,
            'total_price'         => $total,
            'selected_facilities' => $selectedFacilities,
            'payment_status'      => 'Pending',
        ]);

        $room->update(['availability' => 0, 'status' => 'Occupied']);

        return redirect()->route('check', ['nik' => $request->nik])->with('success', 'Reservasi berhasil! Silakan lakukan pembayaran di bawah.');
    }

    // Upload payment proof by guest
    public function uploadProof(Request $request)
    {
        $request->validate([
            'booking_id'     => 'required|exists:bookings,id',
            'payment_proof'  => 'required|image|max:2048',
            'payment_method' => 'required|string',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        $path = $request->file('payment_proof')->store('payments', 'public');

        $booking->update([
            'payment_proof'  => $path,
            'payment_method' => $request->payment_method,
            'payment_status' => 'Reviewing',
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim! Admin akan memverifikasi dalam 1x24 jam.');
    }
}