<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'asv_id',
        'label',
    ];
}
