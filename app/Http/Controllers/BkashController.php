<?php

namespace App\Http\Controllers;

use App\Models\BkashPayment;
use App\Models\Ticket;
use App\Models\UserTicket;
use App\Services\BkashApi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BkashController extends Controller
{
    public function bkash_callback(Request $request)
    {
        // $bkash = new BkashApi;
        // $response = $bkash->callback($request);
        // dump($response);
        // if($response['success'])
        // {
        //     $data = json_decode($response['data'], true);
        //     $payId = $data['paymentID'];
            $payment = BkashPayment::where('payment_id', 'TR00111F1683824802537')->first();
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
                    return redirect('/')->with('message', "Ticket is booked!");
                }
            }
        // }
        return abort(403, "Payment not found!");
    }
}
