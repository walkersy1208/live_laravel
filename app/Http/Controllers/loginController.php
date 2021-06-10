<?php

namespace App\Http\Controllers;

use App\Articles;
use App\BlogUsers;
use App\Http\Requests\loginRequest;
use App\profile;
use Illuminate\Support\Facades\Auth;

class loginController extends baseController
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('login');
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();

            return redirect('/articles');
        }
    }

    public function test_collection()
    {
        //1.把从数据库中获取的集合的email转换为大写
        //$all_users = BlogUsers::all();
        /*$users_upper_email = $all_users->map(function ($user) {
            $user->email = strtoupper($user->email);

            return $user;
        })->toArray();*/

        //2. 从集合中获取所有girl的数据
        /*$all_profile = Profile::all();
        $users_girls = $all_profile->filter(function ($user) {
            return $user->sex === 'boy';
        });

        dd($users_girls->toArray());*/

        //集合->find("x"), 查找ID为x的集合
        // $all_profile = Profile::all();
        // dd($all_profile->find('2')->toArray());

        //注意：profile没有括号
        //one to one
        //dd(BlogUsers::find('2')->profile->toArray());
        //one to many

        //Illuminate\Database\Eloquent\Collection
        //dd(BlogUsers::where('id', '1')->get());
        //不加括号返回的是Illuminate\Database\Eloquent\Collection，对象集合
        //dd(BlogUsers::find('2')->article);

        //加括号返回的Illuminate\Database\Eloquent\Relations\HasMany
        //dd(BlogUsers::find('2')->article());

        //!!!加了括号可以继续使用连贯的sql操作,可以进行再帅选操作!!
        //dd(BlogUsers::find('2')->article()->where('user_id', '2')->get()->toArray());

        //这两种执行方法得到的结果是一样
        // dd(BlogUsers::find('2')->article->toArray());
        //dd(BlogUsers::find('2')->article()->get()->toArray());

        //使用withcount,这里的article，对应model中的关系模型名字 public function article()
        //所执行的sql如下
        //select `blog_users`.*, (select count(*) from `articles` where `blog_users`.`id` = `articles`.`user_id`) as `article_count` from `blog_users` where `id` = '2'
        // var_dump(BlogUsers::withCount([
        //     'article',
        // ])->where('id', '2')->get()->toArray());

        /* 这种withcount得不到结果
        var_dump(BlogUsers::find(2)->withCount([
            'article',
        ])->get()->toArray());
        */

        //var_dump(BlogUsers::find('2')->profile->toArray());
        //var_dump(profile::find('2', ['sex', 'job'])->user->toArray());
        //var_dump(profile::where('id', '2')->first()->user->toArray());

        //var_dump(profile::with('user')->get()->toArray());
        /*使用with 获取两张表的数据,获取的数据如下，在profile 表中获取的数据中添加user=xxx的数据*/
        /*array(7) {
            ["id"]=>
            int(1)
            ["user_id"]=>
            int(2)
            ["sex"]=>
            string(4) "girl"
            ["job"]=>
            string(3) "php"
            ["created_at"]=>
            string(27) "2021-03-17T04:08:57.000000Z"
            ["updated_at"]=>
            string(27) "2021-03-17T04:13:39.000000Z"
            ["user"]=>
            array(15) {
              ["id"]=>
              int(2)
              ["name"]=>
              string(16) "Lawrence Lebsack"
              ["email"]=>
              string(20) "ndenesik@example.org"
              ["password"]=>
              string(60) "$2y$10$/dxwZRKhrRExHaweO7kFruK.DSCbwRaPYEJoh9G/4WP8FaPAOmKMi"
              */

        //所执行的sql语句如下：
        //select * from `profiles`
        //select * from `blog_users` where `blog_users`.`id` in (1, 2, 3)

        //延迟加载
        /*$article_users = BlogUsers::find('2')->article;
        foreach ($article_users as $article) {
            var_dump($article->title);
        }*/
    }

    public function check(loginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $login_info = Auth::attempt(['email' => $email, 'password' => $password]);

        if ($login_info) {
            session()->flash('success', 'Login Success!');

            return redirect('/articles');
        } else {
            //使用withInput()，old value 才能起作用
            return back()->withErrors([
                'password' => '密码错误',
            ])->withInput();
        }
    }
}
