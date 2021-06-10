<?php

namespace App\spa;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class BlogUsers extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    protected $table = 'blog_users';
    protected $guarded = [];

    //one to one
    public function profile()
    {
        return $this->hasOne(profile::class, 'user_id', 'id');
    }

    public function article()
    {
        return $this->hasMany(Articles::class, 'user_id', 'id');
    }

    public function notice()
    {
        return $this->belongsToMany(\App\Admin\Notice::class, 'user_notices', 'user_id', 'notice_id');
    }
}
