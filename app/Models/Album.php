<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Collection;
use App\Models\Date;
use App\Models\Person;
use App\Models\Image;
use App\Models\Comment;

class Album extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'salsah_id',
        'title',
        'label',
        'signature',
        'description',
    ];

    public function collections()
    {
        return $this->belongsToMany(Collection::Class);
    }

    public function dates()
    {
        return $this->belongsToMany(Date::Class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::Class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::Class);
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::Class);
    }
}
