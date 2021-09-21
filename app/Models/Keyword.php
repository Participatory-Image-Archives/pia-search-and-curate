<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\Location;

class Keyword extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
        'description'
    ];

    public function images()
    {
        return $this->belongsToMany(Image::Class);
    }
}
