<?php

namespace App\Http\Controllers;

use App\Http\Requests\registerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class registerController extends baseController
{
    public function index()
    {
        return view('register');
    }

    public function uniqueEmail(Request $request)
    {
        $user_input_email = $request->input('email');
        $result = DB::table('blog_users')->where('email', $user_input_email)->exists();

        echo json_encode([
            'code' => $result ? '-1' : '0',
            'result' => $result,
        ]);

        exit;
    }

    public function register(registerRequest $request)
    {
        //验证前端传递过来的数据 required
        //dd($request);
        $p1 = $request->input('password_confirmation');
        $password = $request->input('password');
        $email = $request->input('email');

        //必须使用 expception，不然服务器会抛出422错错误
        // try {
        //     $this->validate(
        //         $request,
        //         [
        //             'email' => 'required|unique:blog_users|email',
        //             'password' => 'required|min:5|confirmed',
        //         ]
        //     );
        // } catch (ValidationException  $e) {
        //     //dd('return back');
        //     //echo json_encode(['error' => '1111']);
        //     echo json_encode(['errors' => $e->errors(), 'code' => '-1']);
        //     exit;
        // }

        $result = \App\blogUsers::create([
                'name' => 'testing',
                'password' => $password,
                'email' => $email,
                'avatar' => 'xxxxx',
        ]);

        session()->flash('success', 'register success!');

        return redirect('/articles');
    }
}
