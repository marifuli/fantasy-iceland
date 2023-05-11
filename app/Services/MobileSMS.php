<?php 
namespace App\Services;

use Illuminate\Support\Facades\Http;

class MobileSMS {
    static public function send(
        string $reciever, string $message, 
    )
    {
        Http::post(
            "https://sms.brainwavebd.com/api/sms/send", [
                "apiKey" => env('SMS_BRAINWAVEBD_API_KEY'),
                "contactNumbers" => $reciever,
                "senderId" => "BulkSms",
                "textBody" => $message
            ]
        );
    }
}
