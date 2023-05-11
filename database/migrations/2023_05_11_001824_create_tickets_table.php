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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->string('base_ticket_image');
            $table->text('description');
            $table->json('off_days_list')->nullable();
            $table->integer('price_in_cents');
            $table->integer('discount_price_in_cents')->default(0);
            $table->integer('cant_buy_after_days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
