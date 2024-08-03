<?php

namespace App\Http\Controllers;

use App\KraSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KraController extends Controller
{
    public function addSettings(Request $request)
    {
        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'cash_endpoint' => 'required',
                'invoice_endpoint' => 'required',
                'sell_return_endpoint' => 'required',
                'token' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $settings = KraSetting::updateOrCreate([
                'business_id' => auth()->user()->business_id,
            ],
            [
                'business_id' => auth()->user()->business_id,
                'cash_endpoint' => $request->cash_endpoint,
                'invoice_endpoint' => $request->invoice_endpoint,
                'sell_return_endpoint' => $request->sell_return_endpoint,
                'token' => $request->token,
            ]);
            
        } else {
            $settings = KraSetting::where('business_id', auth()->user()->business_id)->first();
        }
        
        return view('kra_settings.add', compact('settings'));
    }

    public function sandbox()
    {
        if (request()->method() == 'POST') {
            $url = request()->endpoint;
            $token = request()->token ?? 'ZxZoaZMUQbUJDljA7kTExQ==2023';
            $payload = request()->payload;
            $data = json_decode(request()->payload);
    
            $ch = curl_init($url);
        
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $token
            ));
        
            $response = curl_exec($ch);  
            
            if ($response === false) {
                $response = curl_error($ch);
            }
    
            curl_close($ch);
            
            return view('kra_test.index')->with(compact('response', 'url', 'token', 'payload'));
        } else {
            $response = '';
            return view('kra_test.index')->with('response');
        }
    }
}