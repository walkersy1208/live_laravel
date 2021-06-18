<?php

namespace App\Http\Controllers\spa;

use App\Admin\Carousel;
use App\Admin\Role;
use App\Articles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\adminAddRequest;
use App\Http\Requests\Admin\adminEditRequest;
use App\Http\Requests\roleRequest;
use App\likes;
use App\Notifications\userLikeNotification;
use App\PassportAdmin\Admin;
use App\proxy\TokenProxy;
use App\spa\BlogUsers;
use App\Tags;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class spaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'login', 'spa_articles', 'get_articles', 'tag_articles', 'article_sort', 'article_detail']]);
    }

    public function article_detail(Request $request)
    {
        if (!empty($request->input('article_id'))) {
            $articles = Articles::with('user')->with('tags')->where('id', $request->input('article_id'))->first();
            //$tags = Tags::withCount('article')->get();

            if (!empty($articles)) {
                return response()->json([
                    'articles' => $articles,

                    'code' => '0',
                ]);
            } else {
                return response()->json([
                    'code' => '-1',
                ]);
            }
        }
    }

    public function article_sort(Request $request)
    {
        $condition = $request->input('sortBy');
        if (!empty($condition) && in_array($condition, ['date', 'hot'])) {
            $orderby = $condition == 'date' ? 'display_priority' : 'count_likes';
            $articles = Articles::with('user')->orderBy($orderby, 'desc')->paginate(5);
            $tags = Tags::withCount('article')->get();

            return response()->json([
                'articles' => $articles,
                'tags' => $tags,
            ]);
        } else {
            return response()->json([
               'code' => '-1',
            ]);
        }
    }

    public function destroy(Request $request, $article)
    {
        // try {
        //     $this->authorize('delete', $article);
        // } catch (AuthorizationException $e) {
        //     return response()->json([
        //         'code' => '-1',
        //         'msg' => '你并没有权限操作删除权限',
        //     ]);
        // }

        $article = Articles::find($article);
        $code = 0;
        if (!$article->delete()) {
            $code = '-1';
        }

        return response()->json([
            'code' => $code,
        ]);
    }

    public function logout(Request $request)
    {
        $code = '-1';
        if ($request->user()->token()->revoke()) {
            $code = '0';
        }

        // dd($request->user());

        return response()->json([
            'code' => $code,
        ]);
    }

    public function read_notifications()
    {
        if (Auth::check()) {
            $items = Auth::user()->unreadNotifications()->paginate(3)->map(function ($item) {
                $item['created'] = \Carbon\Carbon::parse($item['created_at'])->diffForHumans();

                return $item;
            });

            return response()->json([
                'page' => Auth::user()->unreadNotifications()->paginate(3),
                'items' => $items->toArray(),
                'code' => '0',
            ]);
        }
    }

    public function read_all_notifications()
    {
        if (Auth::check()) {
            $items = Auth::user()->notifications()->paginate(3)->map(function ($item) {
                $item['created'] = \Carbon\Carbon::parse($item['created_at'])->diffForHumans();

                return $item;
            });

            return response()->json([
                'page' => Auth::user()->notifications()->paginate(3),
                'items' => $items->toArray(),
                'code' => '0',
            ]);
        }
    }

    public function mark_read(Request $request)
    {
        if ($request->input('nid')) {
            $read_notification = Auth::user()->notifications()->where('id', $request->input('nid'));
            if ($read_notification) {
                $read_notification->update(['read_at' => Carbon::now()]);
            }
        }
    }

    public function get_articles(Request $request)
    {
        $condition = $request->input('sortBy');
        $orderby = 'display_priority';
        if (!empty($condition) && in_array($condition, ['date', 'hot'])) {
            $orderby = $condition == 'date' ? 'display_priority' : 'count_likes';
        }

        if (!empty($request->input('search'))) {
            $search_value = $request->input('search');
            $articles = Articles::with('user')->with('tags')
            ->where('title', 'like', '%'.$search_value.'%')
            ->orWhere('content', 'like', '%'.$search_value.'%')
            ->orderBy($orderby, 'desc')->paginate(5);

            if ($articles->isEmpty()) {
                $articles = Articles::with('user')->with('tags')->orderBy($orderby, 'desc')->paginate(5);
            }
        } else {
            $articles = Articles::with('user')->with('tags')->orderBy($orderby, 'desc')->paginate(5);
        }

        $tags = Tags::withCount('article')->get();

        return response()->json([
            'articles' => $articles,
            'tags' => $tags,
        ]);
    }

    public function update(Request $request, $article_id)
    {
        $title = $request->input('title');
        $content = $request->input('content');

        //hande file upload
        if (!empty($content)) {
            if ((strpos($content, 'src') !== false)) {
                $pattern = '/<img((?!src).)*src[\s]*=[\s]*[\'"](?<src>[^\'"]*)[\'"]/i';
                preg_match_all($pattern, $content, $out);
                $tmp_files = [];

                foreach ($out['src'] as $file) {
                    if (strpos($file, 'data:image') !== false) {
                        $new_file = $this->saveBase64Image($file);
                        $new_file = str_replace('./upload/', '/upload/', $new_file);
                        if ($new_file !== false) {
                            $content = str_replace($file, $new_file, $content);
                            $tmp_files[] = $new_file;
                        }
                    }
                }
            }
        }

        //post提交过来的tags
        $tags = $request->input('tags');
        $article = Articles::find($article_id);
        $article->title = $title;
        $article->content = $content;
        $res_article = $article->save();

        if (!empty($tags)) {
            $tags_arr = explode(',', $tags);
            $tmp_ids = [];
            foreach ($tags_arr as $tag) {
                // update tag count
                $exists_tag = Tags::where('name', $tag)->first();
                if (empty($exists_tag)) {
                    $new_tag = Tags::create([
                        'name' => $tag,
                    ]);
                    Tags::where('id', $new_tag->id)->increment('count_num');
                    $tmp_ids[] = $new_tag->id;
                } else {
                    $tmp_ids[] = $exists_tag->id;
                }
            }
            $article->tags()->sync($tmp_ids);
        }

        if ($res_article) {
            //session()->flash('success', 'Edit Success!');
            return response()->json([
                'code' => '0',
                'update_record' => Articles::with('tags')->where('id', $article_id)->first(),
            ]);
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

            $picname = mt_rand(0, 1000).time().".{$type}";
            $new_file = $file_path.$picname;
            //echo $new_file;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $file)))) {
                return $new_file;
            } else {
                return false;
            }
        }
    }

    public function articles_add(Request $request)
    {
        //not finish vaildation
        $title = $request->input('title');
        $content = $request->input('content');
        $tags = $request->input('tags');

        if (!empty($content)) {
            if ((strpos($content, 'src') !== false)) {
                $pattern = '/<img((?!src).)*src[\s]*=[\s]*[\'"](?<src>[^\'"]*)[\'"]/i';
                preg_match_all($pattern, $content, $out);
                $tmp_files = [];

                foreach ($out['src'] as $file) {
                    $new_file = $this->saveBase64Image($file);
                    $new_file = str_replace('./upload/', '/upload/', $new_file);
                    if ($new_file !== false) {
                        $content = str_replace($file, $new_file, $content);
                        $tmp_files[] = $new_file;
                    }
                }
            }
        }

        if (Auth::check()) {
            $user_id = Auth::user()->id;

            $max_display_priority = Articles::max('display_priority');
            $article = Articles::create([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'display_priority' => $max_display_priority + 1,
            ]);

            if ($article) {
                //Handle Tags
                if (!empty($tags)) {
                    $tags_arr = explode(',', $tags);
                    foreach ($tags_arr as $tag) {
                        $tag_result = Tags::firstOrCreate([
                            'name' => $tag,
                        ]);
                        //update tag count
                        Tags::where('id', $tag_result->id)->increment('count_num');
                        $article->tags()->attach($tag_result->id);
                    }
                }

                return response()->json([
                    'code' => '0',
                ]);
            }
        } else {
        }
    }

    public function index(Request $request)
    {
        return view('spa.index');
    }

    public function tag_articles(Request $request)
    {
        $tag_id = $request->input('tag_id');

        if (!empty($tag_id)) {
            $condition = $request->input('sortBy');
            $orderby = 'display_priority';

            if (!empty($condition) && in_array($condition, ['date', 'hot'])) {
                $orderby = $condition == 'date' ? 'display_priority' : 'count_likes';
            }

            $rst = DB::table('tags_articles')->where('tags_id', $tag_id)->get();
            $articles_ids = $rst->pluck('article_id');

            $articles = Articles::with('tags')->with('user')->whereIn('id', $articles_ids)->orderBy($orderby, 'desc')->paginate(5);

            $tags = Tags::withCount('article')->get();

            return response()->json([
                'code' => 0,
                'articles' => $articles,
                'tags' => $tags,
            ]);
        } else {
            return response()->json([
                'code' => -1,
                'articles' => [],
                'tags' => [],
            ]);
        }
    }

    public function user(Request $request)
    {
        return response()->json(['user' => $request->user()->toArray()]);
    }

    public function login(Request $request, TokenProxy $http)
    {
        $result = $http->proxy('password', [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ]);

        //dd($request->input('username'));

        $code = -1;
        $data = [];
        if (empty($result['error'])) {
            $code = 0;
            //*** no need to find user
            //$login_user = BlogUsers::where('email', $request->input('username'))->first();
            if (!empty($login_user)) {
                // $result['user_id'] = $login_user->id;
                // $result['user_email'] = $login_user->email;
                $data = $result;
            }
        }

        return response()->json([
            'code' => $code,
            'data' => $result,
        ]);
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

    public function checkUserLike(Request $request)
    {
        if (Auth::user()->id) {
            $article_id = $request->input('article_id');
            $like_model = Likes::where([
                'user_id' => Auth::user()->id,
                'article_id' => $article_id,
            ]);

            $user_is_liked = false;
            if ($like_model->exists()) {
                $user_is_liked = true;
            }

            return response()->json([
                'code' => '0',
                'user_is_liked' => $user_is_liked,
            ]);
        } else {
            return response()->json([
                'code' => '-1',
            ]);
        }
    }

    public function handlelike(Request $request)
    {
        //接受user_id
        //$user_id = $request->input('user_id');
        if (empty(Auth::user()->id)) {
            echo json_encode(['info' => '请先登录']);
            exit;
        }

        $user_id = Auth::user()->id;
        $article_id = $request->input('article_id');

        try {
            $this->validate($request, [
                'article_id' => 'required|integer',
            ]);
        } catch (ValidationException  $e) {
            var_dump($e->errors());
        }

        $action = '';
        $like_model = Likes::where([
            'user_id' => $user_id,
            'article_id' => $article_id,
        ]);

        if ($like_model->exists()) {
            $result = $like_model->delete();
            Articles::where('id', $article_id)->decrement('count_likes', '1');
        //使文章数增减1
        } else {
            $result = Likes::firstOrCreate([
                'user_id' => $user_id,
                'article_id' => $article_id,
            ]);

            //增加消息通知
            //获取文章作者模型
            $article_model = Articles::find($article_id);

            $article_user_id = $article_model->user->id;
            $article_user_model = BlogUsers::find($article_user_id);
            //在文章作者中添加通知消息，把登录作者文章名字添加到notification
            $article_user_model->notify(new userLikeNotification($article_model->title));
            //wasRecentlyCreate,新增为true，反之为false
            //$result = $result->wasRecentlyCreated;

            //使文章数增加1
            Articles::where('id', $article_id)->increment('count_likes');
        }

        $article = Articles::select('count_likes')->where('id', $article_id)->first();

        echo json_encode([
            'code' => '0',
            'action' => $action,
            'count_likes' => $article->count_likes,
        ]);
        exit;

        //user  article like 关系
        //article 对 Like 一篇文章可以有多个like
        //like 和 user 关系，一个用户只能对一篇文章有like
        //echo json_encode(['user_id' => $user_id, 'article_id' => $article_id]);
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
    }
}
