<?php

namespace App\Http\Controllers\passport;

use App\Admin\Carousel;
use App\Admin\Role;
use App\Articles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\adminAddRequest;
use App\Http\Requests\Admin\adminEditRequest;
use App\Http\Requests\Admin\adminLoginRequest;
use App\Http\Requests\roleRequest;
use App\PassportAdmin\Admin;
use App\proxy\TokenProxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class apiController extends Controller
{
    public function __construct()
    {
        //Request::header('111111');
        //Config::set('auth.admin_jwt', App\Admin\Admin::class);
       // $this->middleware('auth:api', ['except' => ['index', 'login', 'showRole']]);
        //$this->middleware('auth.jwt:admin_jwt', ['except' => ['index', 'login', 'showRole', 'test', 'logout']]);
        //$this->middleware('auth:admin_jwt', ['except' => ['index', 'login', 'showRole', 'logout']]);
    }

    public function index()
    {
        return view('api.login');
    }

    public function test()
    {
        return view('spa.login');
    }

    public function login(adminLoginRequest $request, TokenProxy $proxy)
    {
        dd($proxy);

        $admin_user = Admin::where('name', $request->input('name'))
        ->firstOrFail();

        // if (!Hash::check($request->input('password'), $admin_user->password)) {
        //     return $this->failed('密码不正确');
        // }

        $token = $admin_user->createToken('API Access')->accessToken;
        dd($token);
    }

    public function me()
    {
        dd(auth('api'));
    }

    public function logout()
    {
        //从playload 中获取ID
        $auth_backend_user_id = auth('admin_jwt')->payload()->toArray()['sub'];
        //$login_user = Admin::find($auth_backend_user_id);
        //从playload 的 ID 中生成一个auth user
        //Auth::guard('admin_jwt')->byId($auth_backend_user_id);
        //必须加上true從新reflash token
        //Auth::guard('admin_jwt')->logout(true);
    }

    public function changeSwitchStatus(Request $request)
    {
        if (empty($request->input('id')) || empty($request->input('is_visible'))) {
            return response()->json([
                'code' => '-1',
                'msg' => 'ERROR_CHANGE_SWITCH_STATUS',
            ]);
        }

        $result = Carousel::find($request->input('id'))->update([
            'is_visible' => $request->input('is_visible'),
        ]);

        if ($result) {
            return response()->json([
                'code' => '0',
            ]);
        }
    }

    public function sort_table(Request $request)
    {
        $sort_data = $request->input('sort_data');
        $tmp_arr = [];
        $tmp_result = [];
        foreach ($sort_data as $data) {
            $id = $data['id'];
            $priority = $data['priority'];
            $result = Carousel::find($id)->update(
                [
                    'display_priority' => $priority,
                ]
            );

            if (!$result) {
                echo json_encode([
                    'code' => '-1',
                    'msg' => 'UPDATE_PRIORITY_FAIL',
                ]);
                exit;
            }
        }

        echo json_encode([
            'code' => '0',
            'msg' => 'UPDATE_PRIORITY_SUCCESS',
        ]);
    }

    public function carousel()
    {
        //$all_carousel = Carousel::where('is_visible', 'Y')->orderBy('created_at', 'desc')->get()->toArray();
        $all_carousel = Carousel::orderBy('display_priority', 'desc')->get()->toArray();

        return view('api.carousel', [
           'carousel_data' => json_encode($all_carousel),
        ]);
    }

    public function del_carousel(Request $request)
    {
        if (empty($request->input('id'))) {
            return response()->json([
                'code' => '-1',
                'msg' => 'ERROR_DELETE_CAROUSEL_DEL',
            ]);
        }

        $result = Carousel::find($request->input('id'))->delete();

        return response()->json([
            'code' => '0',
        ]);
    }

    public function add_carousel(Request $request)
    {
        $base64_image = $request->input('file');
        $mobile_base64_image = $request->input('mobile_file');
        $inserted_file_name = '';
        $mobile_inserted_file_name = '';
        if ($base64_image) {
            $inserted_file_name = $this->saveBase64Image($base64_image);
        }

        if ($mobile_base64_image) {
            $mobile_inserted_file_name = $this->saveBase64Image($mobile_base64_image);
        }

        //查找当前db中display_priority
        $max_priority = Carousel::max('display_priority');
        $insert_data = [
            'is_visible' => $request->input('is_visible'),
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'display_priority' => $max_priority + 1,
        ];

        if ($inserted_file_name !== false) {
            $insert_data['image_url'] = $inserted_file_name;
        }

        if ($mobile_inserted_file_name !== false) {
            $insert_data['mobile_image_url'] = $mobile_inserted_file_name;
        }

        $result = Carousel::create(
            $insert_data
        );

        if ($result) {
            echo json_encode([
                    'code' => '0',
                    'msg' => 'INSERT_DB_SUCCESS',
                ]);
            exit;
        }
    }

    public function saveBase64Image($file)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $file, $result)) {
            $type = $result[2];

            $file_path = './upload/'.date('Ymd', time()).'/';
            if (!file_exists($file_path)) {
                mkdir($file_path, 755, true);
            }

            $picname = mt_rand(0, 99).time().".{$type}";
            $new_file = $file_path.$picname;

            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $file)))) {
                return $new_file;
            } else {
                return false;
            }
        }
    }

    public function hanlde_upload(Request $request)
    {
        if ($request->input('id')) {
            $base64_image_name = '';
            $mobile_base64_image_name = '';
            $del_old_files = [];

            if ($request->input('file')) {
                $base64_image = $request->input('file');
                $base64_image_name = $this->saveBase64Image($base64_image);
            }
            if ($request->input('mobile_file')) {
                $base64_image = $request->input('mobile_file');
                $mobile_base64_image_name = $this->saveBase64Image($base64_image);
            }

            //get data from db
            $update_data_arr = [
                'is_visible' => $request->input('is_visible'),
                'name' => $request->input('name'),
                'url' => $request->input('url'),
            ];

            $carousel_model = Carousel::where('id', $request->input('id'));
            $carousel_row = $carousel_model->first()->toArray();

            if (!empty($base64_image_name)) {
                $update_data_arr['image_url'] = $base64_image_name;
                if (!empty($carousel_row['image_url'])) {
                    $del_old_files[] = $carousel_row['image_url'];
                }
            }

            if (!empty($mobile_base64_image_name)) {
                $update_data_arr['mobile_image_url'] = $mobile_base64_image_name;
                if (!empty($carousel_row['mobile_image_url'])) {
                    $del_old_files[] = $carousel_row['mobile_image_url'];
                }
            }

            $reuslt = $carousel_model->update($update_data_arr);
            if ($reuslt) {
                //Delete old files
                foreach ($del_old_files as $old_image) {
                    if (file_exists(public_path().$old_image)) {
                        unlink(public_path().$old_image);
                    }
                }

                return response()->json([
                    'code' => '0',
                    'msg' => 'DB_UPDATE_UPLOAD_IMAGE_SUCCESS',
                ]);
            } else {
                return response()->json([
                    'code' => '-1',
                    'msg' => 'DB_UPDATE_UPLOAD_IMAGE_FAIL',
                ]);
            }
        }
    }

    /*public function hanlde_upload(Request $request)
    {
        $base64_image = $request->input('file');
        $upload_image_id = $request->input('id');
        $carousel_model = Carousel::where('id', $upload_image_id);
        if ($base64_image) {
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)) {
                $type = $result[2];

                $file_path = './upload/'.date('Ymd', time()).'/';
                if (!file_exists($file_path)) {
                    mkdir($file_path, 755, true);
                }

                $picname = mt_rand(0, 99).time().".{$type}";
                $new_file = $file_path.$picname;
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image)))) {
                    if ($carousel_model) {
                        //Delete old image
                        $old_image = $carousel_model->first()->image_url;
                        $result = $carousel_model->update([
                            'image_url' => $new_file,
                            'is_visible' => $request->input('is_visible'),
                            'name' => $request->input('name'),
                            'url' => $request->input('url'),
                        ]);

                        if ($result) {
                            if (file_exists(public_path().$old_image)) {
                                unlink(public_path().$old_image);
                            }

                            return response()->json([
                                'code' => '0',
                                'msg' => 'DB_UPDATE_UPLOAD_IMAGE_SUCCESS',
                            ]);
                        }
                    }
                } else {
                    //return response()->json(['s' => 'fail']);
                }
            } else {
                //not image upload
            }
        } else {
            $result = $carousel_model->update([
                //'image_url' => $request->input('image'),
                'is_visible' => $request->input('is_visible'),
                'name' => $request->input('name'),
                'url' => $request->input('url'),
            ]);

            if ($result) {
                return response()->json([
                    'code' => '0',
                    'msg' => 'DB_UPDATE_UPLOAD_IMAGE_SUCCESS',
                 ]);
            }
        }
    }*/

    //onchange image to upload function
    /*public function hanlde_upload(Request $request)
    {
        $base64_image = $request->input('file');
        $upload_image_id = $request->input('id');

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)) {
            $type = $result[2];

            $ff = date('Y-m-d', time());
            $file_path = './upload/'.date('Ymd', time()).'/';
            if (!file_exists($file_path)) {
                mkdir($file_path, 700);
            }
            $picname = mt_rand(0, 99).time().".{$type}";
            $new_file = $file_path.$picname;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image)))) {
                $result = Carousel::where('id', $upload_image_id)->update([
                    'image_url' => $new_file,
                ]);

                if ($result) {
                    return response()->json([
                        'code' => '0',
                        'msg' => 'DB_UPDATE_UPLOAD_IMAGE_SUCCESS',
                    ]);
                }
                //return response()->json(['s' => $new_file]);
            } else {
                //return response()->json(['s' => 'fail']);
            }
        } else {
            return false;
        }
    }*/

    public function upload_image()
    {
        return view('api.upload');
    }

    public function articles_status_change(Request $request)
    {
        $aid = $request->input('aid');
        $status = $request->input('status');
        $result = Articles::withOutGlobalScope('avaiable')->where('id', $aid)->update([
            'status' => $status,
        ]);

        if ($result) {
            $response = [
                'code' => '0',
                'msg' => 'SUCCESS_UPDATE_ARTICLES_AVALABLE',
            ];
        } else {
            $response = [
                'code' => '-1',
                'msg' => 'FAIL_UPDATE_ARTICLES_AVALABLE',
            ];
        }

        return response()->json($response);
    }

    public function articles_review()
    {
        $all_articles = Articles::withOutGlobalScope('avaiable')->orderBy('created_at', 'desc')->get();
        $tmp = [];
        foreach ($all_articles as $article) {
            if ($article['status'] == '-1') {
                $article['status_mess'] = '不通过';
                $article['status_tag_class'] = 'danger';
            } elseif ($article['status'] == '1') {
                $article['status_mess'] = '通过';
                $article['status_tag_class'] = 'success';
            } else {
                $article['status_mess'] = '等待';
                $article['status_tag_class'] = 'warning';
            }

            $tmp[] = $article;
        }

        return view('api.show_articles', ['all_articles' => $all_articles]);
    }

    public function showAdmin()
    {
        //Show All Admin Users
        $all_admin_users = Admin::orderBy('created_at', 'DESC')->get()->toArray();

        return view('api.show', ['admin_users' => $all_admin_users]);
    }

    public function showRole()
    {
        $all_roles = Role::orderBy('created_at', 'DESC')->get();

        return view('api.role', ['all_roles' => $all_roles]);
    }

    public function add_admin(adminAddRequest $request)
    {
        $name = $request->input('name');
        $password = bcrypt($request->input('password'));

        $result = Admin::create(
            [
                'name' => $name,
                'password' => $password,
            ],
        );
        echo json_encode(['code' => '0', 'msg' => 'ADD_ADMIN_SUCCESS']);
        //echo json_encode(['code' => '0', 'msg' => $name.'===='.$password]);
        exit;
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

    public function deleteRole($id)
    {
        if (empty($id)) {
            echo json_encode(['code' => '-1', 'msg' => 'Error Request']);
            exit;
        }

        $is_deleted = Role::find($id)->delete();
        if ($is_deleted) {
            echo json_encode(['code' => '0', 'msg' => $is_deleted]);
            exit;
        } else {
            echo json_encode(['code' => '-1', 'msg' => 'DELETE ERROR']);
            exit;
        }
    }

    public function addRole(roleRequest $roleRequest)
    {
        $last_insert_role = Role::create($roleRequest->all());
        if ($last_insert_role) {
            echo json_encode(['code' => 0, 'msg' => 'success']);
            exit;
        }
    }

    public function admin_edit(adminEditRequest $request, $id)
    {
        $request_password = $request->input('password');
        $new_password = bcrypt($request_password);
        $admin = Admin::find($id);
        $admin->name = $request->input('name');
        $admin->password = $new_password;
        $result = $admin->save();
        if ($result) {
            echo json_encode(['code' => 0, 'msg' => 'RESET_PASSWORD_SUCCESS']);
            exit;
        } else {
            echo json_encode(['code' => -1, 'msg' => 'RESET_PASSWORD_FAIL']);
            exit;
        }
        //$hash1 = bcrypt('333333');
        //$hash2 = bcrypt('test');
        //$is_true = Hash::check('333333', $admin_info['password']);
        //$2y$10$5WSfuWLXa5XMuKrUFuyh.e4cCxESO0qBAX4XUDo5q2jF237r61IK6
        //$is_true = == $admin_info['password'];
        //echo json_encode(['code' => 0, 'msg' => $is_true]);
    }
}
