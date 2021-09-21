<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Comment extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'comment',
    ];

    public function images()
    {
        return $this->belongsToMany(Image::Class, 'image_comment', 'image_id', 'comment_id');
    }
}
