<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MovieTicket extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function movie()
    {
        return $this->belongsTo(Movies::class, 'movie_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    static public function user_tickets($id)
    {
        return self::query()->where('date', '>', now())->where('user_id', $id)->latest();
    }
    static public function is_booked(int $movie_id, string $time_slot, int $hall_package_id, string $seat_no): bool
    {
        $date = Carbon::parse($time_slot);
        $formattedDate = $date->format('Y-m-d H:i:s');
        $key = "hall_package_id" . $hall_package_id .
            "movie_id" . $movie_id .
            "date" . $formattedDate .
            "seat_no" . $seat_no ;
        return (
            !Cache::get($key) &&
            self::query()->where('hall_package_id', $hall_package_id)
            ->where('movie_id', $movie_id)
            ->where('date', $formattedDate)
            ->where('seat_no', $seat_no)
            ->first()
        );
    }
}
