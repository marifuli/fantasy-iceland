<?php

namespace App\Http\Controllers;

use App\Models\BkashPayment;
use App\Models\Movies;
use App\Models\Ticket;
use App\Services\BkashApi;
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
    public function ticket_buy($id, Request $request)
    {
        if(!auth()->check()) {
            session(['after_login' => $request->url()]);
            return redirect(route('login')); 
        }
        $user = auth()->user();
        $ticket = Ticket::query()->findOrFail($id);
        $bkash = new BkashApi();
        $getPaymentUrlResponse = $bkash->createPayment($ticket->price, 'Ticket #' . $ticket->id, $user->phone);
        // dd($getPaymentUrlResponse);
        if($getPaymentUrlResponse['success']) 
        {
            BkashPayment::create([
                'user_id' => auth()->user()->id,
                'payment_id' => $getPaymentUrlResponse['id'],
                'product' => 'ticket',
                'metadata' => [
                    'date' => $request->date,
                    'ticket' => $ticket->id,
                ]
            ]);
            return redirect($getPaymentUrlResponse['data']['data']);
        }
    }
    public function movie($id)
    {
        return view('pages.movie', [
            'movie' => Movies::query()->findOrFail($id),
        ]);
    }
    public function after_login()
    {
        $redirect = '/home';
        if(session('after_login'))
        {
            $redirect = session('after_login');
            session(['after_login' => null]);
        }
        return redirect($redirect);
    }
}
