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
        Schema::table('movie_tickets', function (Blueprint $table) {
            $table->string('price')->default(0);
            $table->string('quantity')->default(1);
        });
        Schema::table('user_tickets', function (Blueprint $table) {
            $table->string('price')->default(0);
            $table->string('quantity')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie_tickets', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('quantity');
        });
        Schema::table('user_tickets', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('quantity');
        });
    }
};
