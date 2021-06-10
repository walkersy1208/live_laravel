<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $guarded = [];

    public function getImageUrlAttribute($value)
    {
        //dd($value);
        return str_replace('./', '/', $value);
    }

    public function getMobileImageUrlAttribute($value)
    {
        //dd($value);
        return str_replace('./', '/', $value);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
}
