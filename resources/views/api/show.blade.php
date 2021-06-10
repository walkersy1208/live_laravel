@extends('layouts.app')
@include("admin.header")
@section('content')
    <div style="clear:both;"></div>
    <div class="row main_content_row">
        <div class="left_menu">
            <menu-component
                :current_url="'{{request()->path()}}'"
            >
            </menu-component>
        </div>
        <div class="right_content">
            <div>
                <?php
                   //token params
                   $url_params = $_SERVER["QUERY_STRING"];
                ?>
                @component("api.breadcrumb", [
                    "data"=>[ 
                        "首页"=>"/api/showAdmin?".$url_params,
                        "管理员列表"=>"/api/xxxx?".$url_params,
                    ]
                ]) 
                @endcomponent
               
            </div>
            <div class="row">
                <div>
                    <!-- <modal-component title="提示信息" :is_input=true :fields="[
                            {'name':'name','type':'text','ph':'请输入管理员名称','vm':'name'},
                            {'name':'password','type':'password','ph':'请输入管理员密码','vm':'password'},
                            {'name':'repassword','type':'password','ph':'请输入管理员密码','vm':'password'},
                        ]" msg="添加成功" status="success" request_url="/api/add_admin">
                        <div slot="add_role_btn" class="btn btn-primary" style="with:100px;">添加管理员</div>
                    </modal-component> -->
                    <article-component
                        type="edit" 
                        title="提示信息" 
                        :fields="
                        [
                            {'name':'name','type':'text','ph':'请输入管理员名称','vm':'name'},
                            {'name':'password','type':'password','ph':'请输入管理员密码','vm':'password'},
                            {'name':'repassword','type':'password','ph':'请输入管理员密码','vm':'password'},
                        ]" 
                        msg="添加成功" 
                        status="success"
                        request_url="/api/add_admin"
                        redirect_url=""
                        :btn_style_type="{
                            icon:'el-icon-circle-plus',
                            class:'addBtn'
                        }"
                    >
                    </article-component> 

                </div>
            </div>
            <div style="clear:both;"></div>
            <table id="list_table" class="table table-striped border">
                <thead>
                    <tr style="font-weight:bold;font-size:20px">
                        <th>Admin Name</th>
                        <th>Create Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admin_users as $user)
                        <tr>
                            <td>{{ $user['name'] }}</td>
                            <td>{{$user["created_at"]}}</td>
                            <td>
                                <div class="btn-group"  role="group">
                                    <div>
                                        <!-- <edit-component title="提示信息" :is_input=true :fields="[
                                                {'name':'name','type':'text','ph':'请输入管理员名称','vm':'name','val':'{{ $user['name'] }}' },
                                                {'name':'password','type':'password','ph':'请输入管理员密码','vm':'password','val':'' },
                                                {'name':'repassword','type':'password','ph':'请再次输入管理员密码','vm':'password','val':'' },
                                                ]" msg="修改成功" status="success"
                                            request_url="/api/edit_admin/{{ $user['id'] }}">
                                        </edit-component> -->
                                        <article-component
                                                type="edit" 
                                                title="提示信息" 
                                                :fields="
                                                [
                                                    {'name':'name','type':'text','ph':'请输入管理员名称','vm':'name','val':'{{ $user['name'] }}' },
                                                    {'name':'password','type':'password','ph':'请输入管理员密码','vm':'password','val':'' },
                                                    {'name':'repassword','type':'password','ph':'请再次输入管理员密码','vm':'password','val':'' },
                                                ]" 
                                                msg="添加成功" 
                                                status="success"
                                                request_url="/api/edit_admin/{{ $user['id'] }}"
                                                redirect_url=""
                                                btn_style_type=""
                                            >
                                        </article-component> 
                                    </div>
                                    <div>
                                        <comfirm-component request_url="/api/delete_admin/{{ $user['id'] }}">
                                        </comfirm-component>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="clear:both;">

        </div>
    </div>

@section('js')
    $("#list_table").DataTable({
        "order": ['2','asc'],
        "pageLength": 5,         //初始化显示几条数据
       
    });
@endsection
@endsection
