<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTicket extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];   
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    } 
    public function user()
    {
        return $this->belongsTo(User::class);
    } 
    static public function user_tickets($id)
    {
        return self::query()->where('user_id', $id)->latest();
    }
}
