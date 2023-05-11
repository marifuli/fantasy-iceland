<?php

namespace App\Http\Controllers;

use App\Models\BkashPayment;
use App\Models\HallPackage;
use App\Models\Movies;
use App\Models\MovieTicket;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserTicket;
use App\Services\BkashApi;
use App\Services\MobileSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    public function movie_buy($id, Request $request)
    {
        if(!auth()->check()) {
            session(['after_login' => $request->url()]);
            return redirect(route('login')); 
        }
        $seats = explode(',', $request->seat);

        $user = auth()->user();
        $ticket = Movies::query()->findOrFail($id);
        $package = HallPackage::query()->findOrFail($request->package);
        $bkash = new BkashApi();
        $getPaymentUrlResponse = $bkash->createPayment(
            ($package->price_in_cents / 100) * count($seats), 'Movie Ticket #' . $ticket->id, $user->phone
        );
        // dd($getPaymentUrlResponse);
        if($getPaymentUrlResponse['success']) 
        {
            BkashPayment::create([
                'user_id' => auth()->user()->id,
                'payment_id' => $getPaymentUrlResponse['id'],
                'product' => 'movie',
                'metadata' => [
                    'movie' => $ticket->id,
                    'time_slot' => $request->time_slot,
                    'package' => $request->package,
                    'seat' => $seats,
                ]
            ]);
            return redirect($getPaymentUrlResponse['data']['data']);
        }
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
    public function ticket_download($id)    
    {
        $ticket = UserTicket::query()->findOrFail($id);
        $img_src = $ticket->base_ticket_image;
        dump(
            storage_path('app/public/' . $img_src)
        );
        dd(
            mime_content_type(storage_path('app/public/' . $img_src))
        );

        header('Content-type: image/jpeg');
        $image = imagecreatefromjpeg('image.jpg');
        $textcolor = imagecolorallocate($image, 255, 255, 255);
        $font_file = 'myfont.ttf';
        $custom_text = '';
        imagettftext($image, 225, 0, 3450, 3000, $textcolor, $font_file, $custom_text);
        imagejpeg($image);
        imagedestroy($image); 
    }
    public function movie_download($id)    
    {
        
    }

    public function verify_tickets_submit(Request $request)
    {
        if($request->check_otp)
        {
            if(session('code') == $request->check_otp)
            {
                return redirect()->route('verify-tickets.list', $request->check_otp);
            }else 
            {
                $err = "Wrong OTP code";
                return view('pages.verify_tickets', [
                    'emailOrPhone' => $request->emailOrPhone, 'err' => $err ?? null,
                ]);
            }
        }
        $numberOrMail = $request->emailOrPhone;
        $user = User::where('email', $request->emailOrPhone)->orWhere('phone', $numberOrMail)->first();
        if(!$user)
        {
            return redirect()->back()->withErrors(["User not found!"]);
        }
        $code = rand(10_000, 99_999);
        session(['code' => $code]);
        session(['phone' => $numberOrMail]);
        $mess = "Your OTP code is: " . $code;
        if(
            $numberOrMail 
            && filter_var($numberOrMail, FILTER_VALIDATE_EMAIL)
        )
        {
            Mail::raw($mess, function($message) use($numberOrMail) {
                $message->subject("Fantasy Island")->to($numberOrMail);
            });
        }else {
            MobileSMS::send(
                $numberOrMail . '',
                $mess
            );
        }

        return view('pages.verify_tickets', [
            'emailOrPhone' => $numberOrMail, 'error' => $err ?? null,
        ]);
    }
    public function verify_tickets_list($code)
    {
        $numberOrMail = session('phone');
        $user = User::where('email', $numberOrMail)->orWhere('phone', $numberOrMail)->first();
        if(session('code') != $code || !$user) return abort(403);
        $id = $user->id;
        return view('pages.my_tickets', [
            'tickets' => UserTicket::query()->where('user_id', $id)->latest()->get(),
            'movies' => MovieTicket::query()->where('user_id', $id)->latest()->get(),
        ]);
    }
    public function my_tickets()
    {
        $id = auth()->id();
        return view('pages.my_tickets', [
            'tickets' => UserTicket::query()->where('user_id', $id)->latest()->get(),
            'movies' => MovieTicket::query()->where('user_id', $id)->latest()->get(),
        ]);
    }
}
