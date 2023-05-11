<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use App\Models\Ticket;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'tickets' => Ticket::latest()->get(),
            'movies' => Movies::query()
                // ->where('start_at', '>', now())
                // ->where('end_at', '<', now())
                ->latest()->get()
        ]);
    }
    public function ticket($id)
    {
        return view('pages.ticket', [
            'ticket' => Ticket::query()->findOrFail($id),
        ]);
    }
    public function movie($id)
    {
        return view('pages.movie', [
            'movie' => Movies::query()->findOrFail($id),
        ]);
    }
}
