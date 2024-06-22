<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReprintReceiptCount extends Model
{    
    protected $guarded = ['id'];

    protected $table = 'reprint_receipt_counts';
}
