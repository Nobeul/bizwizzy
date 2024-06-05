<?php

namespace App\Http\Controllers;

use App\CoopBankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoopBankController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Coop bank response');
        Log::info($request->all());
        $entry_data = (new CoopBankTransaction())->createNewRecord($request->all());

        if ($entry_data) {
            return response()->json([
                'MessageCode' => 200,
                'Message' => "Successfully received data."
            ]);
        } else {
            return response()->json([
                'MessageCode' => 500,
                'Message' => "Something went wrong. Please try again."
            ]);
        }
    }
}
