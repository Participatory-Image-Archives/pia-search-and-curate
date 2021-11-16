<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Map;
use App\Models\MapEntry;

class MapLayer extends Model
{
    protected $connection = 'pia';

    protected $fillable = [
        'label',
        'map_id'
    ];
    
    /* relations */

    public function map()
    {
        return $this->belongsTo(Map::Class);
    }

    public function mapEntries()
    {
        return $this->hasMany(MapEntry::Class);
    }
}
