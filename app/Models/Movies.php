<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    use HasFactory;
    static public $image_store_path = "movies/images";
    protected $guarded = [];
    protected $casts = [
        'time_slots' => 'array',
    ];
}
