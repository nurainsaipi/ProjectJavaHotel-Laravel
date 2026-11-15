<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    protected $fillable = [
        'room_id','customer_id','checkin_date','checkout_date',
        'total_price','selected_facilities','payment_status','payment_method','payment_note','payment_proof'
    ];
    public function room()     { return $this->belongsTo(Room::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}