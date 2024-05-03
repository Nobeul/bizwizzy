<?php

namespace App\Listeners;

use App\Events\PinnacleSmsEvent;

class SendSms
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  PinnacleSmsEvent  $event
     * @return void
     */
    public function handle(PinnacleSmsEvent $event)
    {
        $this->sendMessage($event->username, $event->password, $event->apikey, $event->senderid, $event->message, $event->phone);
    }

    public function sendMessage($username, $password, $apikey, $senderid, $message, $phone)
    {
        $postfields = [
            'userid' => $username,
            'password' => $password,
            'sendMethod' => 'quick',
            'mobile' => $phone,
            'msg' => $message,
            'senderid' => $senderid,
            'msgType' => 'text',
            'duplicatecheck' => 'true',
            'output' => 'json'
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://smsportal.hostpinnacle.co.ke/SMSApi/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_HTTPHEADER => array(
            'apikey: ' . $apikey,
            'cache-control: no-cache',
            'content-type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        \Log::info('pinnacle sms message = '.$message);
        \Log::info($response);

    }
}
