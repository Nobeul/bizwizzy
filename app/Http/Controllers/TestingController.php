<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Transaction;

class TestingController extends Controller
{
    public function index()
    {
        $auth_token = $this->getAuthToken('tzJbxYiRLIQVGjIpAgk4OTaTnxusTozh', '36PSlEiRSnelAGjJ');
        $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl');
        $header = [
            'Authorization: Bearer '.$auth_token,
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        $data = [
            "ShortCode" => 600983,
            "ResponseType" => "Completed",
            "ConfirmationURL" => "https://bizwizzy.com/confirmation",
            "ValidationURL" => "https://bizwizzy.com/validation",
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        echo $response;
    }
    
    private function getAuthToken($mpesa_consumerkey, $mpesa_consumersecret)
    {
        $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $credentials = base64_encode($mpesa_consumerkey . ":" . $mpesa_consumersecret);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $curl_response = curl_exec($curl);
        $access_token = json_decode($curl_response);
        
        return $access_token->access_token;
    }
    
    public function mailTest()
    {
        Mail::to('nobeul.cse@gmail.com')->send(new TestMail());
        dd('test');
    }

    public function updateDuplicacies()
    {
        $duplicateTransactions = Transaction::whereNotNull('invoice_no')->groupBy('invoice_no')->havingRaw("count('invoice_no') > 1")->select('id', 'invoice_no')->get();
        $total = 0;
        foreach ($duplicateTransactions as $transaction) {
            $total++;
            $transaction->update(['invoice_no' => $transaction->invoice_no.'dup']);
        }

        echo 'Total ' . $total . 'was updated successfully';
    }
}
