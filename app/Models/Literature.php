<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Literature extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
    ];
}
