<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class likes extends Model
{
    protected $guarded = [];

    public function user()
    {
        //return $this->hasOne(BlogUsers::class, 'id', 'user_id');
    }
}
