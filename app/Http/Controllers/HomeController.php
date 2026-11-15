<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('home', compact('availRooms', 'facilities'));
    }

    public function pricing()
    {
        $rooms = Room::orderBy('number')->get();
        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('pricing', compact('rooms', 'availRooms', 'facilities'));
    }

    public function gallery()
    {
        $rooms = Room::orderBy('number')->get();
        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('gallery', compact('rooms', 'availRooms', 'facilities'));
    }

    public function check(Request $request)
    {
        $nik = $request->query('nik', '');
        $booking = null;

        if ($nik) {
            $booking = Booking::join('customers', 'bookings.customer_id', '=', 'customers.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('customers.nik', $nik)
                ->orderByDesc('bookings.created_at')
                ->select(
                    'bookings.*',
                    'customers.name',
                    'customers.nik as customer_nik',
                    'rooms.number as r_no',
                    'rooms.type as r_type',
                    'rooms.price as r_price'
                )
                ->first();
        }

        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('check', compact('booking', 'nik', 'availRooms', 'facilities'));
    }
}
