<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Notice;
use App\BlogUsers;
use App\Http\Controllers\Controller;
use App\Http\Requests\noticeRequest;
use App\Jobs\sendMessage;

class noticeController extends Controller
{
    public function index()
    {
        //$all_notice = Notice::all();
        $all_notices = Notice::all();

        return view('admin.notice', [
            'all_notices' => $all_notices,
        ]);
    }

    public function delete($id)
    {
        if (empty($id)) {
            echo json_encode(['code' => '-1', 'msg' => 'Error Request']);
            exit;
        }

        $is_deleted = Notice::find($id)->delete();
        if ($is_deleted) {
            echo json_encode(['code' => '0', 'msg' => $is_deleted]);
            exit;
        } else {
            echo json_encode(['code' => '-1', 'msg' => 'DELETE ERROR']);
            exit;
        }
    }

    public function edit(noticeRequest $request, $id)
    {
        if (empty($id)) {
            echo json_encode(['code' => '-1', 'msg' => 'Error Request']);
            exit;
        }

        $edit_row = Notice::find($id);
        $edit_row->title = $request->input('title');
        $edit_row->content = $request->input('content');
        $inserted_id = $edit_row->save();
        if ($inserted_id) {
            echo json_encode(['code' => '0', 'msg' => $inserted_id]);
            exit;
        } else {
            echo json_encode(['code' => '-1', 'msg' => 'UPDATE ERROR']);
            exit;
        }
    }

    public function add(noticeRequest $request)
    {
        $notice = Notice::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        //分发队列任务
        dispatch(new sendMessage($notice));

        if ($notice) {
            echo json_encode(['code' => 0, 'msg' => 'success']);
            exit;
        }

        /*$save_to_users = BlogUsers::all()->notice()->saveMany([
        ]);*/

        /*$all_users = BlogUsers::all();
        foreach ($all_users as $user) {
            $add_notice_result = $user->notice()->save([
                        new Notice([
                            'title' => $request->input('title'),
                            'content' => $request->input('content'),
                        ]),
                    ]);

            echo json_encode(['code' => 0, 'msg' => $add_notice_result]);
        }*/
        //$save_to_users = BlogUsers::find('1')->notice->toArray();
    }
}
