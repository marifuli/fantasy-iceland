<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallPackage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'seats' => 'array',
    ];
}
