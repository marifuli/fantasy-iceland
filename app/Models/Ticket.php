<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    static public $image_store_path = "tickets/images";
    /**
     * Note: 
     * cant_buy_after_days => people can't buy the ticket for a date that is after this days
     */

    protected $guarded = [];
    protected $casts = [
        'off_days_list' => 'array'
    ];
}
