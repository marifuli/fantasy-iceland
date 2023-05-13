<?php

namespace App\Http\Controllers;

use App\Models\BkashPayment;
use App\Models\Movies;
use App\Models\MovieTicket;
use App\Models\Ticket;
use App\Models\UserTicket;
use App\Services\BkashApi;
use App\Services\MobileSMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BkashController extends Controller
{
    public function bkash_callback(Request $request)
    {
        $bkash = new BkashApi;
        $response = $bkash->callback($request);
        // dump($response);
        if($response['success'])
        {
            // Cache::forever('data', $response['data']);
            $data = json_decode($response['data'], true);
            // dd($data);
            $payId = $data['paymentID'];
            $payment = BkashPayment::where('payment_id', $payId)->first();
            if($payment)
            {
                if($payment->product === 'ticket')
                {
                    $ticket = Ticket::query()->findOrFail($payment['metadata']['ticket']);
                    $user_ticket = UserTicket::query()->create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $payment->user_id,
                        'date' => Carbon::parse($payment->metadata['date']),
                    ]);
                    $mess = "Dear customer, Your entry Fantasy Island Entry ticket has been purchased successfully. 
                    \n Click here to download the ticket: ". route('ticket.download', $ticket) ."
                    ";
                    if(
                        $payment->user->email 
                        && filter_var($payment->user->email, FILTER_VALIDATE_EMAIL)
                    )
                    {
                        Mail::raw($mess, function($message) use($payment) {
                            $message->subject("Fantasy Island")->to($payment->user->email);
                        });
                    }
                    MobileSMS::send(
                        $payment->user->phone . '',
                        $mess
                    );
                    return redirect('/')->with('message', "Ticket is booked!");
                }
                else if($payment->product === 'movie')
                {
                    $ticket = Movies::query()->findOrFail($payment['metadata']['movie']);
                    $tickets = [];
                    $date = Carbon::parse($payment->metadata['time_slot']);
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    if(is_array($payment['metadata']['seat']))
                    {
                        foreach($payment['metadata']['seat'] as $seat)
                        {
                            $tickets[] = MovieTicket::query()->create([
                                'movie_id' => $ticket->id,
                                'user_id' => $payment->user_id,
                                'hall_package_id' => $payment['metadata']['package'],
                                'date' => $formattedDate,
                                'seat_no' => $seat,
                                'hall_package_seat_id' => 0,
                            ]);    
                        }                        
                    }
                    else {
                        $tickets[] = MovieTicket::query()->create([
                            'movie_id' => $ticket->id,
                            'user_id' => $payment->user_id,
                            'hall_package_id' => $payment['metadata']['package'],
                            'date' => $formattedDate,
                            'seat_no' => $payment['metadata']['seat'],
                            'hall_package_seat_id' => 0,
                        ]);
                    }
                    $links = "";
                    foreach($tickets as $tick)
                    {
                        $links .= "\n " . route('movie.download', $tick);
                    }
                    $mess = "Dear customer, Your have booked ticket".( count($tickets) > 1 ? 's' : '' )." of ". $ticket->name ." of Fantasy Island Magic Movie Theater successfully. 
                    \n Date: ".( $date->format('d F, Y') )."
                    \n Show Time: ".( $date->format('h:i a') )."
                    \n Seats: ".( is_array($payment['metadata']['seat']) ? join(',', $payment['metadata']['seat']) : $payment['metadata']['seat'])."
                    \n Click here to download the ticket".( count($tickets) > 1 ? 's' : '' ).": ". (
                        $links
                    );
                    if(
                        $payment->user->email 
                        && filter_var($payment->user->email, FILTER_VALIDATE_EMAIL)
                    )
                    {
                        Mail::raw($mess, function($message) use($payment) {
                            $message->subject("Fantasy Island")->to($payment->user->email);
                        });
                    }
                    MobileSMS::send(
                        $payment->user->phone . '',
                        $mess
                    );
                    return redirect('/')->with('message', "Movie ticket is booked!");
                }
            }
        }
        // dd($response);
        return redirect('/')->with("error", $response["message"] ?? "Payment not found!");
    }
}
