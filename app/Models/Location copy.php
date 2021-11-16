<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Location extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
        'geonames_id',
        'geonames_url',
        'latitude',
        'longitude',
        'place_id',
    
    ];

    public function images()
    {
        return $this->hasMany(Image::Class);
    }
}
