<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movie_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->foreignId('user_id');
            $table->foreignId('hall_package_id');
            $table->foreignId('hall_package_seat_id');
            $table->timestamp('date');
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_tickets');
    }
};
