<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function location()
    {
        return $this->belongsTo(Location::Class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::Class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::Class);
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::Class, 'image_comment', 'image_id', 'comment_id');
    }
}
