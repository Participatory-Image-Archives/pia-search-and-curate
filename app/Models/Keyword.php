<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\Location;

class Keyword extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
        'description'
    ];

    public function count($label)
    {
        return Keyword::where('label', $label)->get()->count();
    }

    public function images()
    {
        return $this->belongsToMany(Image::Class);
    }
}
