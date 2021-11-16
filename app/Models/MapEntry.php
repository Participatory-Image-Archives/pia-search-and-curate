<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Map;
use App\Models\MapLayer;
use App\Models\MapKey;
use App\Models\Location;
use App\Models\Image;

class MapEntry extends Model
{
    protected $connection = 'pia';

    protected $fillable = [
        'label',
        'type',
        'complex_data',
        'map_layer_id'
    ];

    /* relations */

    public function map()
    {
        return MapLayer::find($this->map_layer_id)->map();
    }

    public function mapLayer()
    {
        return $this->belongsTo(MapLayer::Class);
    }

    public function mapKeys()
    {
        return $this->belongsToMany(MapKey::Class);
    }

    public function location()
    {
        return $this->belongsTo(Location::Class);
    }

    public function image()
    {
        return $this->belongsTo(Image::Class);
    }
}
