<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ObjectType;
use App\Models\ModelType;
use App\Models\Format;
use App\Models\Person;
use App\Models\Date;
use App\Models\Location;
use App\Models\Keyword;
use App\Models\Comment;
use App\Models\Collection;

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

    public function verso()
    {
        return $this->hasOne(Image::Class, 'verso_id');
    }

    public function objectType()
    {
        return $this->hasOne(ObjectType::Class);
    }

    public function modelType()
    {
        return $this->hasOne(ModelType::Class);
    }

    public function format()
    {
        return $this->hasOne(Format::Class);
    }

    public function location()
    {
        return $this->hasOne(Location::Class);
    }

    public function dates()
    {
        return $this->belongsToMany(Date::Class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::Class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::Class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::Class, 'image_collection', 'image_id', 'collection_id');
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::Class, 'image_comment', 'image_id', 'comment_id');
    }
}
