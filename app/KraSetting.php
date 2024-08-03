<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KraSetting extends Model
{   
    protected $table = 'kra_settings';

    protected $guarded = ['id'];
    
}
