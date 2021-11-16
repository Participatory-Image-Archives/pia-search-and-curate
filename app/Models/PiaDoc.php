<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PiaDoc extends Model
{
    protected $connection = 'pia';
    
    protected $fillable = [
        'label',
        'description',
        'content'
    ];

    public function collections()
    {
        return $this->belongsToMany(Collection::Class, 'pia_docs_collections', 'pia_doc_id', 'collection_id');
    }
}
