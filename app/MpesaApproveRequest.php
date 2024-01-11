<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpesaApproveRequest extends Model
{
    protected $guarded = [];
    
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function createData(array $data, $business_id)
    {
        $imageName = time().rand(1,10000).'.'.$data['document']->extension();
        $upload_path = public_path('mpesa_requests');
        $data['document']->move($upload_path, $imageName);

        return self::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'transaction_number' => $data['transaction_number'],
            'document' => $imageName,
            'business_id' => $business_id,
            'status' => 'pending'
        ]);
    }
}
