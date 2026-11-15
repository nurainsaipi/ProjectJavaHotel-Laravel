<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
        });
        Schema::table('facilities', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
};