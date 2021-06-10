<?php

namespace App\admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    protected $guarded = [];
    //protected $hidden = ['password'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // return [];

        return ['role' => 'admin'];
    }

    public function getCreatedAtAttribute($value)
    {
        return  \Carbon\Carbon::parse($value)->diffForHumans();
    }
}
