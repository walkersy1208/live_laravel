<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $guarded = [];

    public function article()
    {
        return $this->belongsToMany(Articles::class, 'tags_articles', 'tags_id', 'article_id');
        //return $this->belongsToMany(Articles::class, 'tags_articles', 'article_id', 'tags_id');
    }
}
