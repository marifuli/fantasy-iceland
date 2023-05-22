<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;
    static public $image_store_path = "tickets/images";
    /**
     * Note: 
     * cant_buy_after_days => people can't buy the ticket for a date that is after this days
     */

    protected $guarded = [];
    protected $casts = [
        'off_days_list' => 'array'
    ];
    public function getPriceAttribute()
    {
        return $this->price_in_cents ? $this->price_in_cents / 100 : 0;
    }
}
