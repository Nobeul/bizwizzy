<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewaySetting extends Model
{
    protected $table = 'gateway_settings';

    protected $guarded = [];

    public function mpesaRequest()
    {
        return $this->hasOne(MpesaApproveRequest::class, 'business_id', 'business_id');
    }

    public static function mpesaCredentials($business_id)
    {
        return self::with('mpesaRequest')->where([
                'provider' => 'mpesa',
                'business_id' => $business_id
            ])->first();
    }
}