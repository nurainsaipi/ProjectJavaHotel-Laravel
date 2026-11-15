<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    public $timestamps = false;
    
    protected $fillable = ['nik','name','phone','email'];
    public function bookings() { return $this->hasMany(Booking::class); }
}