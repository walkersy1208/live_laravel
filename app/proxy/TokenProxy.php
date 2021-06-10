<?php

namespace App\proxy;

use Illuminate\Support\Facades\Http;

class TokenProxy
{
    protected $http = '';

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    //获取请求auth的类型
    public function proxy($grant_type, $data)
    {
        $post_data = array_merge($data, [
            'grant_type' => $grant_type,
            'client_id' => 9,
            'client_secret' => '868CDX8hBHutu5rZRMHHLg4YoL46vnlEwN1WQcRA',
        ]);

        //必须要加上域名
        $domain_name = $_SERVER['HTTP_HOST'];
        $res = $this->http::post($domain_name.'/oauth/token', $post_data);

        //$token_info = json_decode((string) $res->getBody()->getContents(), true);
        return json_decode((string) $res->getBody(), true);
    }
}
