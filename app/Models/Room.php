<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    protected $fillable = ['number','type','price','description','status','availability'];
    public function bookings() { return $this->hasMany(Booking::class); }
}
