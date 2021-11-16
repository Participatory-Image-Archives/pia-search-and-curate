<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\AltLabel;
use App\Models\Comment;
use App\Models\Date;
use App\Models\Literature;
use App\Models\Person;

class Collection extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'salsah_id',
        'label',
        'signature',
        'description',
        'default_image',
        'embedded_video',
        'origin',
    ];

    public function altLabels()
    {
        return $this->belongsToMany(AltLabel::Class, 'collection_alt_label', 'collection_id', 'alt_label_id');
    }

    public function people()
    {
        return $this->belongsToMany(Person::Class);
    }

    public function literatures()
    {
        return $this->belongsToMany(Literature::Class);
    }

    public function dates()
    {
        return $this->belongsToMany(Date::Class);
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::Class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::Class, 'image_collection', 'collection_id', 'image_id');
    }

    public function images_ids()
    {
        return $this->images()->allRelatedIds();
    }
}
