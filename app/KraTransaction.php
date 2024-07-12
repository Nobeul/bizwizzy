<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KraTransaction extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'kra_transactions';

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
    ];
}
