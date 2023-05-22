<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movies extends Model
{
    use HasFactory, SoftDeletes;
    static public $image_store_path = "movies/images";
    protected $guarded = [];
    protected $casts = [
        'time_slots' => 'array',
    ];
    public function getPriceAttribute()
    {
        return $this->price_in_cents ? $this->price_in_cents / 100 : 0;
    }
}
