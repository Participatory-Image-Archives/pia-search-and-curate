<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Collection extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label'
    ];

    public function images()
    {
        return $this->belongsToMany(Image::Class, 'image_collection', 'image_id', 'collection_id');
    }
}
