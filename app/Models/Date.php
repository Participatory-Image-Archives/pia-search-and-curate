<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'date',
        'date_string',
        'type',
    ];
}
