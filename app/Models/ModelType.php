<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelType extends Model
{
    protected $connection = 'pia';
    protected $table = 'models';
    
    protected $fillable = [
        'label',
        'comment',
    ];
}
