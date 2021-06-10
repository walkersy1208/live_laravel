@extends('layouts.app')
@include('admin.header')
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
                    $url_params = $_SERVER['QUERY_STRING'];
                ?>

                @component('api.breadcrumb', [
                        'data' => [
                            '首页' => '/api/showAdmin?' . $url_params,
                            '轮播器列表' => '/api/carousel?' . $url_params,
                        ],
                    ])
                @endcomponent
            </div>
            <div>
                <!-- <modal-component title="提示信息" :is_input=true :fields="[
                    {'name':'name','type':'text','ph':'请输入carousel名字'},
                    {'name':'url','type':'text','ph':'请输入carousel URL'},
                    {'name':'is_visible','type':'radio','ph':'',val:'Y'},
                    {'name':'image_url','type':'image_url','ph':''},
                    {'name':'mobile_image_url','type':'mobile_image_url','ph':''},
                ]" msg="添加成功" status="success" request_url="/api/add_carousel">
                <div slot="add_role_btn"  class="btn btn-primary" style="min-width:80px;">Add</div>
                </modal-component> -->

                <article-component
                        type="edit" 
                        title="提示信息" 
                        :fields="
                        [
                            {'name':'name','type':'text','ph':'请输入carousel名字'},
                            {'name':'url','type':'text','ph':'请输入carousel URL'},
                            {'name':'is_visible','type':'radio','ph':'',val:'Y'},
                            {'name':'image_url','type':'image_url','ph':''},
                            {'name':'mobile_image_url','type':'mobile_image_url','ph':''},
                        ]" 
                        msg="添加成功" 
                        status="success"
                        request_url="/api/add_carousel"
                        redirect_url=""
                        :btn_style_type="{
                            icon:'el-icon-circle-plus',
                            class:'addBtn'
                        }"
                    >
                    </article-component> 
            </div>
          
            <div>
                <tablelist-component :carousel_data="{{ $carousel_data }}"></tablelist-component>
            </div>
        </div>
        <div style="clear:both;">

        </div>

    </div>

@section('js')
    $("#list_table").DataTable({
        "order": ['2','asc'],
    });
@endsection
@endsection
