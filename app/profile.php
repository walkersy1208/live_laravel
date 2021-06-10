<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class profile extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(BlogUsers::class);
        // $this->belongsTo(BlogUsers::class)->select(['name', 'email']);
    }
}
