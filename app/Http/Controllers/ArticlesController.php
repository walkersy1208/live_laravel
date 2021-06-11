<?php

namespace App\Http\Controllers;

use App\Admin\Carousel;
use App\Articles;
use App\BlogUsers;
use App\Http\Requests\articleRequest;
use App\likes;
use App\Notifications\userLikeNotification;
use App\Tags;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $articles = Articles::with('user')->with('tags')->orderBy('created_at', 'desc')->paginate(5);

    //     return view('articles_verison', [
    //         'articles' => $articles,
    //     ]);
    // }

    public function tag_articles(Request $request, $tag_id)
    {
        if (!empty($tag_id)) {
            //$related_articles = Tags::with('article')->where('id', $tag_id)->paginate(1);
            $related_articles = Tags::find($tag_id)->article()->paginate(2);

            $tags = Tags::withCount('article')->get();

            return view('version_articles', [
                'articles' => $related_articles,
                'tags' => $tags,
            ]);
        }
    }

    public function article_sort(Request $request, $condition)
    {
        if (!empty($condition) && in_array($condition, ['date', 'hot'])) {
            $orderby = $condition == 'date' ? 'display_priority' : 'count_likes';
            $articles = Articles::with('user')->orderBy($orderby, 'desc')->paginate(5);
            $tags = Tags::withCount('article')->get();

            return view('version_articles', [
                'articles' => $articles,
                'tags' => $tags,
            ]);
        } else {
            return back();
        }
    }

    public function index(Request $request)
    {
        $articles = Articles::with('user')->orderBy('display_priority', 'desc')->paginate(5);
        $tags = Tags::withCount('article')->get();

        return view('version_articles', [
            'articles' => $articles,
            'tags' => $tags,
        ]);
    }

    public function create_article_tag($tage_name)
    {
        $result = Tags::create([
            'name' => $tage_name,
        ]);
        if ($result) {
            return $result->id;
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

            $article = Articles::create([
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
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

    public function carousel_items()
    {
        $carousel_items = Carousel::where('is_visible', 'Y')->orderby('display_priority', 'desc')->get()->toArray();
        echo json_encode([
            'code' => 0,
            'data' => $carousel_items,
        ]);
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(articleRequest $request)
    {
        if (!Auth::check()) {
            session()->flash('danger', '请先登录');

            return redirect('/login');
        }

        $user_id = Auth::user()->id;

        $result = BlogUsers::find($user_id)->article()->save(new Articles([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]));

        if ($result->id) {  //success
            session()->flash('success', '添加文章成功');

            return redirect('/articles');
        } else {  //fail
            session()->flash('danger', '添加文章失败');

            return back();
        }
    }

    public function mark_read(Request $request)
    {
        if ($request->input('nid')) {
            $read_notification = Auth::user()->notifications()->where('id', $request->input('nid'));
            $code = '0';
            if ($read_notification) {
                $read_notification->update(['read_at' => Carbon::now()]);
            } else {
                $code = '-1';
            }

            return response()->json([
                'code' => $code,
            ]);
        }
    }

    public function read_notifications()
    {
        if (Auth::check()) {
            return view('readNotification', [
                'page' => Auth::user()->unreadNotifications()->paginate(5),
            ]);
        }
    }

    public function read_all_notifications()
    {
        if (Auth::check()) {
            return view('readNotification', [
                'page' => Auth::user()->notifications()->paginate(5),
            ]);
        }
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

        //自己不能点赞自己
        //1,找文章作者
        // $article_user_id = Articles::find($article_id)->user->id;
        // if ($article_user_id == $user_id) {
        //     echo json_encode([
        //         'msg' => '你不能点赞自己',
        //     ]);
        //     exit;
        // }
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
            //dd($article_model->title);
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
        //echo json_encode($result->count_likes);
        // exit;
        echo json_encode([
            'code' => '0',
            //'action' => $action,
            'count_likes' => $article->count_likes,
        ]);
        exit;

        //user  article like 关系
        //article 对 Like 一篇文章可以有多个like
        //like 和 user 关系，一个用户只能对一篇文章有like
        //echo json_encode(['user_id' => $user_id, 'article_id' => $article_id]);
    }

    public function show($id)
    {
        if (!empty($id)) {
            $article_detail = Articles::with('user')->where('id', $id)->first();
            //$user_id = $article_detail->user->id;

            if (!empty(Auth::user())) {
                $user_id = Auth::user()->id;
                //判断这篇文章所属用户是否已经有赞过？
                $user_is_liked = Likes::where('user_id', $user_id)
                                ->where('article_id', $id)->exists();

                return view('articles_detail', [
                    'article_detail' => $article_detail,
                    'is_liked' => $user_is_liked,
                ]);
            } else {    //提示登入页面
                session()->flash('danger', '请先登录');

                return redirect('/login');
                //return redirect('/login')->withErrors('info', '请登录');
            }
        }
    }

    public function article_tags(Request $request)
    {
        $all_tags = DB::table('tags')->get();

        return response()->json([
            'all_tags' => $all_tags,
            'code' => '0',
        ]);
        // $article_id = $request->input('article_id');
        // $article_rel_tags = Articles::find($article_id)->tags->toArray();
        // echo json_encode($article_rel_tags);
        //exit;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Articles $article)
    {
        try {
            $this->authorize('update', $article);
            $article_id = $article->id;

            $article = Articles::find($article_id);

            $form_fields_name = [
                'title' => '题目',
                'content' => '文章内容',
            ];

            return view('articles_edit', [
                'article' => $article,
                'form_fields_name' => $form_fields_name,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'code' => '-1',
                'msg' => '你并没有进行操作更改权限',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(articleRequest $request, $article_id)
    {
        //dd($test->outPut());
        //使用了继承formRequst的articleRequest类，
        //在articleRequest类中已经使用了表单验证，并返回消息

        /*try {
            $this->validate($request, [
                'article_id' => 'required',
                'title' => 'required|max:100',
                'content' => 'required|max:255',
            ]);
        } catch (ValidationException  $e) {
            return redirect()->back()->withErrors($e->errors());
        }*/

        //$article_id = $request->input('article_id');
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
            ]);
            //return redirect('/articles');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */

    //必须显式声明所传递的参数Articles

    public function destroy(Articles $article)
    {
        try {
            $this->authorize('delete', $article);
            // var_dump($article->id);
        } catch (AuthorizationException $e) {
            return response()->json([
                'code' => '-1',
                'msg' => '你并没有权限操作删除权限',
            ]);
        }

        if ($article->delete()) {
            session()->flash('success', 'Delete Success!');

            return back();
        }
    }

    public function notification()
    {
        if (Auth::check()) {
            return view('notification');
        } else {
            return redirect('/login');
        }
    }
}
