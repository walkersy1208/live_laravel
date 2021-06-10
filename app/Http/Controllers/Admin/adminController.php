<?php

namespace App\Http\Controllers\Admin;

use App\admin\Admin;
use App\Admin\Role;
use App\Events\UserLogin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\adminLoginRequest;
use App\Http\Requests\roleRequest;
use Illuminate\Support\Facades\Auth;

class adminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function sendEmail($user)
    {
        event(new UserLogin($user));
    }

    public function index()
    {
        return view('admin.index');
    }

    public function editRole(roleRequest $request, $id)
    {
        if (empty($id)) {
            echo json_encode(['code' => '-1', 'msg' => 'Error Request']);
            exit;
        }

        $role_row = Role::find($id);
        $role_row->name = $request->input('name');
        $role_row->desc = $request->input('desc');
        $inserted_id = $role_row->save();
        if ($inserted_id) {
            echo json_encode(['code' => '0', 'msg' => $inserted_id]);
            exit;
        } else {
            echo json_encode(['code' => '-1', 'msg' => 'UPDATE ERROR']);
            exit;
        }
    }

    public function showRole()
    {
        $all_roles = Role::orderBy('created_at', 'DESC')->get();

        return view('admin.role', ['all_roles' => $all_roles]);
    }

    public function addRole(roleRequest $roleRequest)
    {
        $last_insert_role = Role::create($roleRequest->all());
        if ($last_insert_role) {
            echo json_encode(['code' => 0, 'msg' => 'success']);
            exit;
        }
    }

    public function showAdmin()
    {
        //Show All Admin Users
        $all_admin_users = Admin::all()->toArray();

        return view('admin.show', ['admin_users' => $all_admin_users]);
    }

    public function checklogin(adminLoginRequest $request)
    {
        //dd($request->all());
        $name = $request->input('name');
        $password = $request->input('password');

        //获取jwt token
        //$token = Auth::guard('jwt')->attempt(['name' => $name, 'password' => $password]);
        $is_admin_login = Auth::guard('admin')->attempt(['name' => $name, 'password' => $password]);
        if ($is_admin_login) {
            session()->flash('success', 'login success!');
            $this->sendEmail(Auth::guard('admin')->user());

            return redirect('/articles');
        } else {
            session()->flash('danger', 'login fail!');

            return back();
        }
    }
}
