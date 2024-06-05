<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CoopBankTransaction extends Model
{   
    protected $guarded = [];

    protected $casts = [
        'response' => 'array',
    ];

    public function createNewRecord(array $data)
    {
        $response = false;

        try {
            $existing_entry = $this->findByTransactionId($data['TransactionId']);
    
            if (empty($existing_entry)) {
                $obj = new self;
                $obj->account_no = $data['AcctNo'] ?? null;
                $obj->amount = $data['Amount'] ?? null;
                $obj->booked_balance = $data['BookedBalance'] ?? null;
                $obj->cleared_balance = $data['ClearedBalance'] ?? null;
                $obj->currency = $data['Currency'] ?? null;
                $obj->cust_memo_line1 = $data['CustMemoLine1'] ?? null;
                $obj->cust_memo_line2 = $data['CustMemoLine2'] ?? null;
                $obj->cust_memo_line3 = $data['CustMemoLine3'] ?? null;
                $obj->event_type = $data['EventType'] ?? null;
                $obj->exchange_rate = $data['ExchangeRate'] ?? null;
                $obj->narration = $data['Narration'] ?? null;
                $obj->payment_ref = $data['PaymentRef'] ?? null;
                $obj->posting_date = $data['PostingDate'] ?? null;
                $obj->value_date = $data['ValueDate'] ?? null;
                $obj->transaction_date = $data['TransactionDate'] ?? null;
                $obj->transaction_id = $data['TransactionId'] ?? null;
                $obj->response = $data ?? null;
                $obj->save();
            }

            $response = true;
            
        } catch (Exception $e) {
            Log::error('Coop bank data insertion error. Message: ' . $e->getMessage() . ' File name:' . $e->getFile() . ' Line no: ' . $e->getLine());
            Log::error($e);
        }

        return $response;
    }

    public function findByTransactionId($transaction_id)
    {
        return self::query()->where('transaction_id', $transaction_id)->first();
    }
}
