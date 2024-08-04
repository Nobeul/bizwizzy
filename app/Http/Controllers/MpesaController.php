<?php

namespace App\Http\Controllers;

use App\Business;
use App\GatewaySetting;
use App\MpesaApproveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\MpesaMail;
use App\MpesaTransaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MpesaController extends Controller
{
    public function transactionList(Request $request)
    {
        $cashiers = User::all();
        $transactions = MpesaTransaction::leftJoin('users', 'users.id', '=', 'mpesa_transactions.cashier_id')
            ->orderBy('id', 'desc')
            ->select(
                DB::raw('mpesa_transactions.*'),
                DB::raw("CONCAT(COALESCE(users.surname, ''),' ',COALESCE(users.first_name, ''),' ',COALESCE(users.last_name,'')) as cashier_name")
            );

        if (! empty($request->cashier_id)) {
            $transactions->where('cashier_id', $request->cashier_id);
        }

        if (request()->ajax()) {
            return DataTables::of($transactions)
                ->editColumn('accepted_by_cashier', function ($row) {
                    return $row->cashier_name;
                })
                ->editColumn('status', function ($row) {
                    return ucfirst(strtolower($row->status));
                })
                ->editColumn('transaction_time', function ($row) {
                    return Carbon::createFromFormat('YmdHis', $row->transaction_time)->format('Y-m-d h:i:s A');
                })
                ->make(true);
        }

        return view('mpesa_transactions.index', compact('cashiers'));
    }
    
    public function index()
    {
        if (empty(request()->business_id) || empty(request()->amount)) {
            return response()->json([
                'status' => 400,
                'message' => 'Settings not found',
            ]);
        }

        $mpesaSetting = GatewaySetting::with('mpesaRequest')
                        ->where([
                            'business_id' => auth()->user()->business_id,
                            'provider' => 'mpesa',
                            'status' => 'active'
                        ])->first();

        if (empty($mpesaSetting)) {
            return response()->json([
                'status' => 500,
                'message' => 'Settings not found',
            ]);
        }

        if ($mpesaSetting->status == 'inactive') {
            return response()->json([
                'status' => 500,
                'message' => 'Mpesa is not active',
            ]);
        }

        if ($mpesaSetting->mpesaRequest->status != 'approved') {
            return response()->json([
                'status' => 500,
                'message' => 'Please contact with support to enable Mpesa service',
            ]);
        }

        $auth_token = $this->getAuthToken($mpesaSetting->mpesa_consumerkey, $mpesaSetting->mpesa_consumersecret);
        $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl');
        $header = [
            'Authorization: Bearer '.$auth_token,
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        $data = [
            "ShortCode" => $mpesaSetting->mpesa_shortcode,
            "ResponseType" => "Completed",
            "ConfirmationURL" => "https://bizwizzy.com/confirmation",
            "ValidationURL" => "https://bizwizzy.com/validation",
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response);
    }

    private function getAuthToken($mpesa_consumerkey, $mpesa_consumersecret)
    {
        $url = env('APP_ENV') == 'live' ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

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

    // would check this function later
    public function verifyPayment()
    {
        $mpesaSetting = GatewaySetting::with('mpesaRequest')
                        ->where([
                            'business_id' => auth()->user()->business_id,
                            'provider' => 'mpesa',
                            'status' => 'active'
                        ])->first();

        // https://mfc.ke/mpesa-c2b-api-integration-to-your-php-website/
        $curl_post_data = [   
            "Initiator" => $mpesaSetting->initiator,
            "SecurityCredential" => $mpesaSetting->password,
            "CommandID" => "TransactionStatusQuery",
            "TransactionID" => "SA862DKGXW",
            "PartyA" => 600980,
            "IdentifierType" => "2",
            "ResultURL" => "https://bizwizzy.com/validation",
            "QueueTimeOutURL" => "https://bizwizzy.com/validation",
            "Remarks" => "ok",
            "Occassion" => "ok",
        ];
    
        $data_string = json_encode($curl_post_data);

        $endpoint = env('APP_ENV') == "live" ? "https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query" : "https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query"; 

        $ch2 = curl_init($endpoint);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        $response     = curl_exec($ch2);
        curl_close($ch2);

        //echo "Authorization: ". $response;

        $result = json_decode($response); 
        
        $verified = $result->{'ResponseCode'};
        if($verified === "0"){
            echo "Transaction verified as TRUE";
        }else{
            echo "Transaction doesnt exist";
        }
    }

    public function create(Request $request)
    {
        $data = [];

        if (request()->method() == 'GET') {
            $previousRequest = MpesaApproveRequest::where('business_id', auth()->user()->business_id)->first();
            if (empty($previousRequest) || ($previousRequest && $previousRequest->status != 'approved')) {
                $data['enableMessage'] = 'You need to enable Mpesa service first.';
            } else {
                $gatewaySettings = GatewaySetting::mpesaCredentials(auth()->user()->business_id);
                $data['settings'] = $gatewaySettings;
            }
    
            return view('mpesa_settings.create', $data);
        } else {
            $validator = Validator::make($request->all(), [
                'mpesa_consumerkey' => 'required',
                'mpesa_consumersecret' => 'required',
                'mpesa_shortcode' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            GatewaySetting::updateOrCreate([
                'business_id' => auth()->user()->business_id
            ],[
                'provider' => 'mpesa',
                'mpesa_shortcode' => $request->mpesa_shortcode,
                'mpesa_consumerkey' => $request->mpesa_consumerkey,
                'mpesa_consumersecret' => $request->mpesa_consumersecret,
                // 'till_number' => $request->till_number ?? null,
                'status' => $request->status,
                'business_id' => auth()->user()->business_id,
            ]);

            $data['message'] = 'Successfully saved Mpesa settings';
            $data['gateways'] = GatewaySetting::where(['provider' => 'mpesa', 'business_id' => auth()->user()->business_id])->get();
            return redirect()->back();
        }

    }

    public function mpesaSettings()
    {
        $data['gateway'] = GatewaySetting::with('mpesaRequest')
                        ->where(['provider' => 'mpesa', 'business_id' => auth()->user()->business_id])
                        ->whereHas('mpesaRequest', function ($q) {
                            return $q->where('status', 'approved');
                        })
                        ->first();

        return view('mpesa_settings.index', $data);
    }
    
    public function edit(Request $request)
    {
        $gatewaySettingObj = GatewaySetting::where(['id' => $request->id])->first();
        $gatewaySettingObj->update([
            'mpesa_consumerkey' => $request->mpesa_consumerkey,
            'mpesa_consumersecret' => $request->mpesa_consumersecret,
            'mpesa_shortcode' => $request->mpesa_shortcode,
            'status' => $request->status
        ]);

        return redirect()->back();
    }

    // public function delete($id)
    // {
    //     GatewaySetting::where(['id' => $id])->delete();

    //     $output = ['success' => true, 'msg' => __("lang_v1.deleted_success")];

    //     return $output;
    // }

    public function enableRequest(Request $request)
    {
        if (request()->method() == 'GET') {
            return view('mpesa_settings.enable_form');
        } else {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'transaction_number' => 'required',
                'document' => 'required|mimes:jpeg,jpg,png|max:'. (config('constants.document_size_limit') / 1000)
            ],[
                'document.required' => 'The payment screenshot field is required.'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $previous_request = MpesaApproveRequest::where('business_id', auth()->user()->business_id)->first();
            if ($previous_request) {
                Session::flash('alert-class', 'warning');
                Session::flash('message', 'You already have a pending request. Please wait for the approval or contact support');
                return redirect()->back();
            }
            $createRequest = (new MpesaApproveRequest())->createData($request->all(), auth()->user()->business_id);
            Session::flash('alert-class', 'success');
            Session::flash('message', 'Enable Mpesa requested successfully');
            return redirect()->to('/home');
        }
    }

    public function requestList()
    {
        $requests = MpesaApproveRequest::with('business:id,name')->orderBy('id', 'desc')->get();

        return view('mpesa_requests.index', compact('requests'));
    }

    public function requestDetails($id)
    {
        // Mail::to('nobeul.cse@gmail.com')->send(new MpesaMail());
        // dd('test');
        $request = MpesaApproveRequest::with('business:id,name')->where('business_id', $id)->first();
        if (empty($request)) {
            Session::flash('alert-class', 'danger');
            Session::flash('message', 'Invalid request id found');
            return redirect()->back();
        }
        if (request()->method() == "GET") {
            return view('mpesa_requests.edit', compact('request'));
        }

        $validator = Validator::make(request()->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->update(['status' => request()->status]);

        // if (request()->status == 'approved' || request()->status == 'rejected') {
        //     Mail::to('nobeul.cse@gmail.com')->send(new MpesaMail());
        // }

        Session::flash('alert-class', 'success');
        Session::flash('message', 'Request updated successfully');

        return redirect()->to('mpesa-request-list');
    }

    public function confirmation()
    {
        Log::info('Mpesa confirmation responses');
        Log::info(request()->all());


        $request = request()->all();

        $mpesaTnxObj = new MpesaTransaction();
        $mpesaTnxObj->first_name = $request['FirstName'] ?? '';
        $mpesaTnxObj->middle_name = $request['MiddleName'] ?? '';
        $mpesaTnxObj->last_name = $request['LastName'] ?? '';
        $mpesaTnxObj->status = 'pending';
        $mpesaTnxObj->transaction_type = $request['TransactionType'] ?? '';
        $mpesaTnxObj->transaction_id = $request['TransID'] ?? '';
        $mpesaTnxObj->msisdn = $request['MSISDN'] ?? '';
        $mpesaTnxObj->transaction_amount = $request['TransAmount'] ?? 0;
        $mpesaTnxObj->business_short_code = $request['BusinessShortCode'] ?? '';
        $mpesaTnxObj->bill_ref_number = $request['BillRefNumber'] ?? '';
        $mpesaTnxObj->transaction_time = $request['TransTime'] ?? '';
        $mpesaTnxObj->invoice_number = $request['InvoiceNumber'] ?? '';
        $mpesaTnxObj->org_account_balance = $request['OrgAccountBalance'] ?? '';
        $mpesaTnxObj->third_party_transaction_id = $request['ThirdPartyTransID'] ?? '';
        $mpesaTnxObj->response = json_encode($request) ?? '';
        $mpesaTnxObj->save();
        
    }

    public function checkPayments()
    {
        $status = false;
        $data = null;
        $mpesaSetting = GatewaySetting::with('mpesaRequest')
                        ->where([
                            'business_id' => auth()->user()->business_id,
                            'provider' => 'mpesa',
                            'status' => 'active'
                        ])->first();

        if (! empty ($mpesaSetting)) {

            $amount = (float)str_replace(",", "", request()->amount);

            $pendingTransaction = MpesaTransaction::where([
                    'status' => 'pending',
                    'business_short_code' => $mpesaSetting->mpesa_shortcode,
                    'transaction_amount' => $amount,
                ]);
    
            if (! empty(request()->passed_by) && request()->passed_by != 'null') {
                $pendingTransaction->where('id', '>', request()->passed_by);
            }
    
            $pendingTransaction = $pendingTransaction->first();
            
            if (! empty ($pendingTransaction)) {
                $status = true;
                $data = $pendingTransaction;
            }
        }

        return response()->json([
            'status' => $status,
            'data' => $data
        ]);
    }

    public function capturePayments()
    {
        $status = false;
        $mpesaSetting = GatewaySetting::with('mpesaRequest')
                        ->where([
                            'business_id' => auth()->user()->business_id,
                            'provider' => 'mpesa',
                            'status' => 'active'
                        ])->first();

        if (! empty($mpesaSetting)) {
            $amount = (float)str_replace(",", "", request()->amount);

            $isUpdatedTransaction = MpesaTransaction::where([
                    'status' => 'pending',
                    'business_short_code' => $mpesaSetting->mpesa_shortcode,
                    'transaction_amount' => $amount,
                    'id' => request()->transaction_id
                ])->update([
                    'cashier_id' => auth()->user()->id,
                    'status' => 'compeleted'
                ]);
            
            if (! empty($isUpdatedTransaction)) {
                $status = $isUpdatedTransaction;
            }
        }
        
        return response()->json(['status' => $status]);
    }
}
