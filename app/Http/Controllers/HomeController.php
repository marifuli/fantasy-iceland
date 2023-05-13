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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'tickets' => Ticket::latest()->get(),
            'movies' => Movies::query()
                ->where('end_at', '>', now())
                ->latest()->get()
        ]);
    }
    public function verify_phone(Request $request)
    {
        if(!auth()->user()->phone_verified_at)
        {
            $phone_cache_key = 'phone_sms_sent_' . auth()->user()->phone;
            if($request->code && session('code'))
            {
                // dd($request->code, session('code'));
                if($request->code == session('code'))
                {
                    session(['code' => null]);
                    Cache::forget($phone_cache_key);
                    auth()->user()->update(['phone_verified_at' => now()]);
                    // dd(session('after_login'));
                    return redirect(
                        session('after_login') ?? '/'
                    )->with('message', "Phone number verified!");
                }
                else
                    $error = "Wrong OTP code!";
            }
            if(!Cache::get($phone_cache_key))
            {
                $code = rand(10_000, 99_999);
                session(['code' => $code]);
                $mess = "Your OTP code is: " . $code;
                MobileSMS::send(
                    auth()->user()->phone . '',
                    $mess
                );
                $sent = true;
                Cache::put($phone_cache_key, time(), 59 * 2);
            }
        }else {
            return redirect(session('after_login') ?? '/')->with('message', "Phone number verified!");
        }
        return view('pages.verify.phone', [
            'user' => auth()->user(),
            'error' => $error ?? false,
            'cache' => isset($phone_cache_key) ? time() - (Cache::get($phone_cache_key) ?? 0) : 0,
            'sent' => isset($sent),
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
            session(['after_login' => $request->fullUrl()]);
            return redirect(route('login')); 
        }
        if(!auth()->user()->phone_verified_at)
        {
            if(!session('after_login'))
            {
                session(['after_login' => $request->fullUrl()]);
            }
            return redirect(
                route("verify.phone")
            );
        }
        $user = auth()->user();
        $ticket = Ticket::query()->findOrFail($id);
        $bkash = new BkashApi();
        $amount = $ticket->price;
        $amount = (int) ceil($amount + (1.4 * $amount / 100));
        $getPaymentUrlResponse = $bkash->createPayment($amount, 'Ticket #' . $ticket->id, $user->phone);
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
            session(['after_login' => $request->fullUrl()]);
            return redirect(route('login')); 
        }
        if(!auth()->user()->phone_verified_at)
        {
            if(!session('after_login'))
            {
                session(['after_login' => $request->fullUrl()]);
            }
            return redirect(
                route("verify.phone")
            );
        }

        $seats = explode(',', $request->seat);
        $user = auth()->user();
        $ticket = Movies::query()->findOrFail($id);
        $package = HallPackage::query()->findOrFail($request->package);
        $bkash = new BkashApi();
        $amount = ($package->price_in_cents / 100) * count($seats);
        $amount = (int) ceil($amount + (1.4 * $amount / 100));
        $time_slot = $request->time_slot;
        
        foreach($seats as $seat)
        {
            //- check if the slot available 
            if(
                MovieTicket::is_booked(
                    $ticket->id, $time_slot, $package->id, $seat 
                )
            )
            {
                return redirect()->back()->with("error", "Sorry the seat is booked, select another one");
            }
        }

        $getPaymentUrlResponse = $bkash->createPayment(
            $amount, 'Movie Ticket #' . $ticket->id, $user->phone
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
                    'time_slot' => $time_slot,
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
        $img_src = $ticket->ticket->base_ticket_image;
        if(!$img_src) return abort(404, "Image not found!");
        $storage = storage_path('app/public/' . $img_src);
        $mime = mime_content_type($storage);
        $ext = (@explode('/', $mime))[1];
        $date = Carbon::parse($ticket->date);

        header("Content-type: " . $mime);
        // $imgPath = 'Movie Ticket '. $ticket->id .'.' . $ext;
        $image = null;
        if($ext === 'png')
            $image = imagecreatefrompng($storage);
        else 
        if($ext === 'jpg')
            $image = imagecreatefromjpeg($storage);
        else 
        if($ext === 'jpeg')
            $image = imagecreatefromjpeg($storage);

        $color = imagecolorallocate($image, 255, 255, 255);
                
        // name 
        $string = "Ticket Number: 00000" . $ticket->id;
        $fontSize = 5;
        $x = 40;
        $y = 20;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        // name 
        $string = "Date: " . Carbon::parse($ticket->date)->format('d F, Y');
        $fontSize = 5;
        $x = 40;
        $y = 240;
        imagestring($image, $fontSize, $x, $y, $string, $color);
        // price 
        $string = "Price: " . $ticket->ticket->price_in_cents / 100 . " Tk";
        $fontSize = 5;
        $x = 250;
        $y = 240;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        if($ext === 'png')
            imagepng($image);
        else 
        if($ext === 'jpg' || $ext === 'jpeg')
            imagejpeg($image);
        exit;
    }
    public function movie_download($id)    
    {
        $ticket = MovieTicket::query()->findOrFail($id);
        $img_src = $ticket->movie->base_ticket_image;
        if(!$img_src) return abort(404, "Image not found!");
        $storage = storage_path('app/public/' . $img_src);
        $mime = mime_content_type($storage);
        $ext = (@explode('/', $mime))[1];
        $date = Carbon::parse($ticket->date);

        header("Content-type: " . $mime);
        // $imgPath = 'Movie Ticket '. $ticket->id .'.' . $ext;
        $image = null;
        if($ext === 'png')
            $image = imagecreatefrompng($storage);
        else 
        if($ext === 'jpg')
            $image = imagecreatefromjpeg($storage);
        else 
        if($ext === 'jpeg')
            $image = imagecreatefromjpeg($storage);

        $color = imagecolorallocate($image, 255, 255, 255);
        
        // name 
        $string = "Ticket Number: 00000" . $ticket->id;
        $fontSize = 5;
        $x = 40;
        $y = 20;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        // name 
        $string = "Movie: " . $ticket->movie->name;
        $fontSize = 5;
        $x = 40;
        $y = 200;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        // date 
        $string = "Date: " . $date->format("d F, Y");
        $fontSize = 5;
        $x = 40;
        $y = 220;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        // date 
        $string = "Show Time: " . $date->format("h:i A");
        $fontSize = 5;
        $x = 40;
        $y = 240;
        imagestring($image, $fontSize, $x, $y, $string, $color);
        
        // Seat  
        $string = "Seat No: " . $ticket->seat_no;
        $fontSize = 5;
        $x = 300;
        $y = 240;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        // Price   
        $string = "Price: " . HallPackage::query()->findOrFail($ticket->hall_package_id)->price_in_cents / 100 . " Tk";
        $fontSize = 5;
        $x = 500;
        $y = 240;
        imagestring($image, $fontSize, $x, $y, $string, $color);

        if($ext === 'png')
            imagepng($image);
        else 
        if($ext === 'jpg' || $ext === 'jpeg')
            imagejpeg($image);
        exit;
    }

    public function verify_tickets_submit(Request $request)
    {
        $numberOrMail = $request->emailOrPhone ?? session('phone');
        // dd($request->emailOrPhone, session('phone'));
        if($numberOrMail) session(['phone' => $numberOrMail]);
        $phone_cache_key = 'phone_sms_sent_' . $numberOrMail;
        if($request->check_otp)
        {
            if(session('code') == $request->check_otp)
            {
                Cache::forget($phone_cache_key);
                return redirect()->route('verify-tickets.list', $request->check_otp);
            }else
            {
                return view('pages.verify_tickets', [
                    'emailOrPhone' => $numberOrMail, 
                    'err' => "Wrong OTP code",
                    'cache' => isset($phone_cache_key) ? time() - (Cache::get($phone_cache_key) ?? 0) : 0,
                    'sent' => isset($sent),
                ]);
            }
        }
        $user = User::where('email', $numberOrMail)
            ->orWhere('phone', $numberOrMail)
            ->first();
        if(!$user)
        {
            return redirect()->back()->withErrors(["User not found!"]);
        }
        if(
            !Cache::get($phone_cache_key) 
        )
        {
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
            $sent = true;
            Cache::put($phone_cache_key, time(), 59 * 2);
        }

        return view('pages.verify_tickets', [
            'emailOrPhone' => $numberOrMail, 
            'error' => $err ?? null,
            'cache' => isset($phone_cache_key) ? time() - (Cache::get($phone_cache_key) ?? 0) : 0,
            'sent' => isset($sent),
        ]);
    }
    public function verify_tickets_list($code)
    {
        $numberOrMail = session('phone');
        // dd($numberOrMail);
        $user = User::where('email', $numberOrMail)
            ->orWhere('phone', $numberOrMail)
            ->first();
        // dd(session('code'), $code, $user);
        if(session('code') != $code || !$user) return abort(403);
        $id = $user->id;
        return view('pages.my_tickets', [
            'tickets' => UserTicket::user_tickets($id)->get(),
            'movies' => MovieTicket::user_tickets($id)->get(),
        ]);
    }
    public function my_tickets()
    {
        $id = auth()->id();
        return view('pages.my_tickets', [
            'tickets' => UserTicket::user_tickets($id)->get(),
            'movies' => MovieTicket::user_tickets($id)->get(),
        ]);
    }
    public function get_movie_empty_seats($id, $time_slot, $hall, Request $request)
    {
        return view('common.user-movie-package-selector', [
            'ticket' => Movies::query()->findOrFail($id),
            'time_slot' => $time_slot,
            'package' => HallPackage::query()->findOrFail($hall),
        ]);
    }
}
