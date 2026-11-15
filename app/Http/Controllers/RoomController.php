<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Facility;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('number')->get();
        $availRooms = Room::where('availability', 1)->orderBy('number')->get();
        $facilities = Facility::orderBy('id')->get();
        return view('admin.manage-rooms', compact('rooms', 'availRooms', 'facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number'      => 'required|string|max:10|unique:rooms,number',
            'type'        => 'required|in:Single,Double,Deluxe,Suite',
            'price'       => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Room::create([
            'number'      => $request->number,
            'type'        => $request->type,
            'price'       => $request->price,
            'description' => $request->description,
            'status'      => 'Clean',
            'availability'=> 1,
        ]);

        return redirect()->route('admin.manage-rooms')->with('added', 'Kamar baru berhasil ditambahkan!');
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'number'      => 'required|string|max:10|unique:rooms,number,' . $room->id,
            'type'        => 'required|in:Single,Double,Deluxe,Suite',
            'price'       => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $room->update([
            'number'      => $request->number,
            'type'        => $request->type,
            'price'       => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.manage-rooms')->with('updated', 'Data kamar berhasil diperbarui!');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.manage-rooms')->with('deleted', 'Kamar berhasil dihapus!');
    }
}
