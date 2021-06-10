<?php

namespace App\PassportAdmin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;
    protected $guarded = [];

    //protected $hidden = ['password'];

    public function findForPassport($username)
    {
        return $this->where('name', $username)->first();
    }
}
