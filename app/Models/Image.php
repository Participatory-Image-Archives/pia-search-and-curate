<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Image extends Model
{
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
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected $appends = ['resource_url'];

    /**
     * Get the location associated with the image.
     */
    public function location()
    {
        return Location::find($this->location_id);
    }

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/images/'.$this->getKey());
    }
}
