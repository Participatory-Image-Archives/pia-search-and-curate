<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\Keyword;

class Image extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'salsah_id',
        'oldnr',
        'signature',
        'title',
        'original_title',
        'file_name',
        'original_file_name',
        'salsah_date',
        'sequence_number',
        'location_id',
        'collection',
        'verso',
        'objecttype',
        'model',
        'format',
    ];

    public function keywords()
    {
        return $this->belongsToMany(Keyword::Class);
    }
}
