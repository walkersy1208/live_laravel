<?php

namespace App\Http\Controllers;

class baseController extends Controller
{
    protected $success_msg = '成功';
    protected $err_msg = '失败';

    public function __construct()
    {
    }

    public function responseMsg($code, $msg, $data = null)
    {
        /*
        $info = [];
        if (!empty($data)) {
            $info['data'] = $data;
        }

        $info['code'] = $code;
        $info['msg'] = $msg;

        return json_encode($info);
        */

        $ret = ['code' => $code, 'msg' => $msg];
        if (!empty($data)) {
            $ret['data'] = $ret;
        }

        return response()->json($ret);
    }

    public function successMsg($code, $msg = '', $data)
    {
        if (empty($msg)) {
            $msg = $this->success_msg;
        }
        $this->responseMsg($code, $msg, $data);
    }

    public function errMsg($code, $msg = '')
    {
        if (empty($msg)) {
            $msg = $this->err_msg;
        }
        $this->responseMsg($code, $msg);
    }
}
