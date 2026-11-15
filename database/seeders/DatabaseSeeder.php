<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        DB::table('admins')->insertOrIgnore([
            ['username' => 'admin', 'password' => Hash::make('admin123'), 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Rooms
        $rooms = [
            ['number'=>'101','type'=>'Single','price'=>850000,'description'=>'Sanctuary minimalis dengan furnitur kayu jati berukir dan pemandangan taman yang menenangkan.','status'=>'Clean','availability'=>1],
            ['number'=>'102','type'=>'Single','price'=>850000,'description'=>'Sanctuary minimalis dengan furnitur kayu jati berukir dan pemandangan taman yang menenangkan.','status'=>'Clean','availability'=>1],
            ['number'=>'201','type'=>'Deluxe','price'=>1700000,'description'=>'Kamar luas bermotif batik asli dengan teras pribadi menghadap kolam.','status'=>'Clean','availability'=>1],
            ['number'=>'202','type'=>'Deluxe','price'=>1700000,'description'=>'Kamar luas bermotif batik asli dengan teras pribadi menghadap kolam.','status'=>'Clean','availability'=>1],
            ['number'=>'301','type'=>'Suite','price'=>3200000,'description'=>'Suite kerajaan dengan furnitur antik, kolam renang pribadi, dan butler 24 jam.','status'=>'Clean','availability'=>1],
        ];
        foreach ($rooms as $r) {
            DB::table('rooms')->insertOrIgnore(array_merge($r, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // Facilities
        $facilities = [
            ['name'=>'Sarapan Prasmanan','price'=>150000],
            ['name'=>'Spa Tradisional Jawa','price'=>350000],
            ['name'=>'Transfer Bandara','price'=>250000],
            ['name'=>'Pemandu Wisata Budaya','price'=>200000],
        ];
        foreach ($facilities as $f) {
            DB::table('facilities')->insertOrIgnore($f);
        }
    }
}
