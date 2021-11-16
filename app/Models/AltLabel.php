<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AltLabel extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
    ];
}
