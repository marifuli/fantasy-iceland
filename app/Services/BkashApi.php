<?php 
namespace App\Services;

use Illuminate\Http\Request;

class BkashApi {
    private $base_url;

    public function __construct()
    {
        // Sandbox
        $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
        // Live
        //$this->base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta';
    }

    public function authHeaders(){
        return array(
            'Content-Type:application/json',
            'Authorization:' .$this->grant(),
            'X-APP-Key:'.env('BKASH_CHECKOUT_URL_APP_KEY')
        );
    }

    public function curlWithBody($url,$header,$method,$body_data_json){
        $curl = curl_init($this->base_url.$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $body_data_json);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function grant()
    {
        $header = array(
            'Content-Type:application/json',
            'username:'.env('BKASH_CHECKOUT_URL_USER_NAME'),
            'password:'.env('BKASH_CHECKOUT_URL_PASSWORD')
        );
        $header_data_json=json_encode($header);

        $body_data = array('app_key'=> env('BKASH_CHECKOUT_URL_APP_KEY'), 'app_secret'=>env('BKASH_CHECKOUT_URL_APP_SECRET'));
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/token/grant',$header,'POST',$body_data_json);

        $token = json_decode($response)->id_token;

        return $token;
    }
    public function createPayment(int $amount, $orderId, $number)
    {
        try {
            $header =$this->authHeaders();

            $website_url = "http://127.0.0.1:8000";

            $body_data = array(
                'mode' => '0011',
                'payerReference' => $number,
                'callbackURL' => route('bkash.callback'),
                'amount' => $amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $orderId // you can pass here OrderID
            );
            $body_data_json = json_encode($body_data);

            $response = $this->curlWithBody('/tokenized/checkout/create',$header,'POST',$body_data_json);

            $response = json_decode($response);
            $redirectUrl = $response->bkashURL;

            return ['success' => True, 'data' => ['data' => $redirectUrl], 'message' => "url is generate successfully", 'id' => $response->paymentID];
        } catch (\Throwable $e) {

            return ['success' => False, 'data' => null, 'message' => $e->getMessage()];
        }
    }

    public function executePayment($paymentID)
    {

        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID
        );
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/execute',$header,'POST',$body_data_json);

        $res_array = json_decode($response,true);

        if(isset($res_array['trxID'])){
            // your database insert operation
            // save $response

        }

        return $response;
    }

    public function queryPayment($paymentID)
    {

        $header =$this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID,
        );
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/payment/status',$header,'POST',$body_data_json);

        $res_array = json_decode($response,true);

        if(isset($res_array['trxID'])){
            // your database insert operation
            // insert $response to your db

        }

        return $response;
    }

//    public function callback(Request $request)
//    {
//        $allRequest = $request->all();
//        if(isset($allRequest['status']) && $allRequest['status'] == 'failure'){
//            return view('CheckoutURL.fail')->with([
//                'response' => 'Payment Failure'
//            ]);
//
//        }else if(isset($allRequest['status']) && $allRequest['status'] == 'cancel'){
//            return view('CheckoutURL.fail')->with([
//                'response' => 'Payment Cancell'
//            ]);
//
//        }else{
//
//            $response = $this->executePayment($allRequest['paymentID']);
//
//            $arr = json_decode($response,true);
//
//            if(array_key_exists("statusCode",$arr) && $arr['statusCode'] != '0000'){
//                return view('CheckoutURL.fail')->with([
//                    'response' => $arr['statusMessage'],
//                ]);
//            }else if(array_key_exists("message",$arr)){
//                // if execute api failed to response
//                sleep(1);
//                $query = $this->queryPayment($allRequest['paymentID']);
//                return view('CheckoutURL.success')->with([
//                    'response' => $query
//                ]);
//            }
//
//            return view('CheckoutURL.success')->with([
//                'response' => $response
//            ]);
//
//        }
//
//    }

    public function callback(Request $request)
    {
       try {
           $allRequest = $request->all();
           if(isset($allRequest['status']) && $allRequest['status'] == 'failure'){
               return ['success' => False, 'data' => null, 'message' => "Payment Failure"];

           }else if(isset($allRequest['status']) && $allRequest['status'] == 'cancel'){

               return ['success' => False, 'data' => null, 'message' => 'Payment Cancel'];
           }else{

               $response = $this->executePayment($allRequest['paymentID']);

               $arr = json_decode($response,true);

               if(array_key_exists("statusCode",$arr) && $arr['statusCode'] != '0000'){

                   return ['success' => False, 'data' => null, 'message' => $arr['statusMessage']];
               }else if(array_key_exists("message",$arr)){
                   // if execute api failed to response
                   sleep(1);
                   $query = $this->queryPayment($allRequest['paymentID']);

                   return ['success' => True, 'data' => $query, 'message' => "payment is done successfully"];
               }

               return ['success' => True, 'data' => $response, 'message' => "payment is done successfully"];
           }

       } catch (\Exception $e) {

           return ['success' => False, 'data' => null, 'message' => $e->getMessage()];
       }

    }

    public function getRefund(Request $request)
    {
        return view('CheckoutURL.refund');
    }

    public function refundPayment(Request $request)
    {
        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $request->paymentID,
            'amount' => $request->amount,
            'trxID' => $request->trxID,
            'sku' => 'sku',
            'reason' => 'Quality issue'
        );

        $body_data_json = json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/payment/refund',$header,'POST',$body_data_json);

        // your database operation
        // save $response

        return $response;
    }
}
