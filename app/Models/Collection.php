<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Collection extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label'
    ];

    public function images()
    {
        return $this->hasMany(Image::Class);
    }
}
