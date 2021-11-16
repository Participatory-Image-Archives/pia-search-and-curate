<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Map;

class MapKey extends Model
{
    protected $connection = 'pia';

    protected $fillable = [
        'label',
        'icon'
    ];

    /* relations */

    public function map()
    {
        return $this->belongsTo(Map::Class);
    }

    public function mapEntries()
    {
        return $this->belongsToMany(MapEntry::Class);
    }
}
