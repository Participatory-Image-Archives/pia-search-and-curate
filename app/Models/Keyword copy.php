<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\AltLabel;

class Keyword extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
        'description'
    ];

    public function images()
    {
        return $this->belongsToMany(Image::Class);
    }

    public function altLabels()
    {
        return $this->belongsToMany(AltLabel::Class, 'keyword_alt_label');
    }
}
