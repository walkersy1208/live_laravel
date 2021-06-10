<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $table = 'articles';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(BlogUsers::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return  \Carbon\Carbon::parse($value)->diffForHumans();
    }

    public function likes()
    {
        return $this->hasOne(Likes::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'tags_articles', 'article_id', 'tags_id');
        //return $this->belongsToMany(tags::class, 'tags_articles', 'tags_id', 'article_id');
    }

    //重写父类boot函数，添加scope
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('avaiable', function (Builder $builder) {
            $builder->whereIn('status', [1]);
        });
    }
}
