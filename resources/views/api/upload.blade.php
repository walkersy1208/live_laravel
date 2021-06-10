@extends('layouts.app')
@section('content')
    <div style="clear:both;"></div>
    <div class="row main_content_row">
        <div class="left_menu">
            <menu-component></menu-component>
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
                <upload-component></upload-component>
            </div>
        </div>
        <div style="clear:both;">
        </div>
    </div>

@section('js')
    //console.log(localStorage.getItem("jwt_token"));
    $("#list_table").DataTable({
        "order": ['2','asc'],
    });
@endsection
@endsection
