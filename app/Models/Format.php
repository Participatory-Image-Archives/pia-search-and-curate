<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
        'comment',
    ];
}
