<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkashPayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'metadata' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
