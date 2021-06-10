<?php

namespace App\Policies;

use App\Articles;
use App\BlogUsers;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        // var_dump('22222');
       // exit;
    }

    //第一个user 使用的是登录用户的ID
    public function update(BlogUsers $user, Articles $articles)
    {
        return $user->is_admin || $user->id === $articles->user_id;
    }

    public function delete(BlogUsers $user, Articles $articles)
    {
        //var_dump('111');
        //是管理员或者是原作者可以删除
        return  $user->is_admin || $user->id === $articles->user_id;
    }
}
