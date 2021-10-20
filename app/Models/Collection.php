<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Collection extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
        'signature',
        'origin'
    ];

    public function images()
    {
        return $this->belongsToMany(Image::Class, 'image_collection', 'collection_id', 'image_id');
    }

    public function images_ids()
    {
        return $this->images()->allRelatedIds();
    }
}
