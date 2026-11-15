<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('number', 10)->unique();
            $table->enum('type', ['Single','Double','Deluxe','Suite']);
            $table->integer('price')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['Clean','Dirty','Inspected','Occupied'])->default('Clean');
            $table->tinyInteger('availability')->default(1);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rooms'); }
};
