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
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/locations/'.$this->getKey());
    }
}
