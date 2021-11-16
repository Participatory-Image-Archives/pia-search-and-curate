<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MapKey;
use App\Models\MapLayer;
use App\Models\MapEntry;

class Map extends Model
{
    protected $connection = 'pia';

    protected $fillable = [
        'label',
        'tiles'
    ];
    
    /* relations */

    public function mapKeys()
    {
        return $this->hasMany(MapKey::Class);
    }

    public function mapLayers()
    {
        return $this->hasMany(MapLayer::Class);
    }

    public function linkedLayers()
    {
        return $this->belongsToMany(MapLayer::Class, 'map_linked_map_layer');
    }

    public function mapEntries()
    {
        return $this->hasManyThrough(MapEntry::Class, MapLayer::Class);
    }
}
