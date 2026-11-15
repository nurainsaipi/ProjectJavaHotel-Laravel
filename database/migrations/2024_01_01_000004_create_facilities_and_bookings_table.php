<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('price')->default(0);
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('total_price')->default(0);
            $table->json('selected_facilities')->nullable();
            $table->enum('payment_status', ['Pending','Paid','Refunded'])->default('Pending');
            $table->string('payment_method', 50)->nullable();
            $table->text('payment_note')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('facilities');
    }
};
