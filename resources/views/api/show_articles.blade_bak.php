@extends('layouts.app')
@include("admin.header")
@section('content')
    <div style="clear:both;"></div>
    <div class="row main_content_row">
        <div class="left_menu">
            <menu-component></menu-component>
        </div>
        <div class="right_content">
            <div>
                <?php 
                    //$token string
                    $url_params = $_SERVER['QUERY_STRING'];
                ?>
                @component('api.breadcrumb', [
                    'data' => [
                        '首页' => '/api/showAdmin?' . $url_params,
                        '轮播器列表' => '/api/articles_review?' . $url_params,
                    ],
                ])
                @endcomponent
            </div>
            <div style="clear:both;"></div>
            <table id="list_table" class="table table-striped border">
                <thead>
                    <tr style="font-weight:bold;font-size:20px">
                        <th>Article Title</th>
                        <th>Create Date</th>
                        <th>Status</th>
                        <th>Select</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_articles as $articles)
                        <tr>
                            <td>{{ mb_substr($articles['title'], 0, 14, 'utf-8') . '...' }}</td>
                            <td>{{ $articles['created_at'] }}</td>

                            <td>
                                <span class="badge badge-{{ $articles['status_tag_class'] }}">
                                    {{ (string) $articles['status_mess'] }}
                                </span>
                            </td>
                            <td>
                                <select-component 
                                    aid="{{ $articles['id'] }}" 
                                    :all_select_data="[
                                        {label:'Approval',value:'1'},
                                        {label:'Pending',value:'0'},
                                        {label:'Disapproval',value:'-1'},
                                    ]" :data_status="{{ $articles['status'] }}">
                                </select-component>
                            </td>
                            <td>
                                <div class="btn-group"  role="group">
                                    <div>
                                       
                                        <article-component
                                            type="edit" 
                                            title="提示信息" 
                                            :fields="
                                            [
                                                {'name':'title','type':'text','ph':'请输入文章标题','val':'{{$articles->title}}'},
                                                {'name':'tags','type':'search','ph':'','request_url':'/','val':'{{$articles->tags}}'},
                                                {'name':'content','type':'editor','ph':'',val:'{{$articles->content}}'}
                                            ]" 
                                            msg="更新成功" 
                                            status="success"
                                            request_url="/api/articles_update/{{$articles->id}}"
                                            redirect_url=""
                                            
                                        >
                                        </article-component> 
                                    </div>
                                    <div>
                                        <comfirm-component 
                                            request_url="/api/delete_admin/"
                                        >
                                        </comfirm-component>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>

@section('js')
    $("#list_table").DataTable({
        "order": ['3','asc'],
        "bPaginate" : true,  
        //"bInfo" : false,
    });
@endsection
@endsection
