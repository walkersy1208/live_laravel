<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
    <title>{{ config('app.name','数码资讯1') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <?php  
    

    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   
    ?>
    <style>
        .row {
            /*display: block !important;*/
            width: 100%;
        }

        .feedback_msg {
            color: red;
        }

        .invalid-feedback {
            color: red;
        }

        .alert.alert-success {
            width: 80%;
            margin: 0 auto;
            margin-bottom: 30px;
        }

        .add_role_btn {
            max-width: 100%;
            float: left;
            padding-top: 10px;
            margin-bottom: 30px;
        }

        .role_error_field_msg {
            color: red;
            font-size: 14px;
        }

        .el-dialog {
            z-index: 999;
        }

        .mb-m {
            margin-bottom: 40px;
        }

        .mb-l {
            margin-bottom: 80px;
        }

        .article_link {
            color: black;
        }

        .btn-group .btn {
            min-width: 80px;
        }

        .abscent {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }


        .el-dialog {
            position: absolute !important;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            margin-top: 0px !important;
        }

        #login_form_wrapper {
            position: absolute;
            width: 30%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 60px 30px;
            border: 2px solid #ccc;
            border-radius: 15px;
        }

        .del_btn,.edit-btn{
            padding:3px 10px;
            font-size:15px;
            margin-left:10px;
        }

        .addBtn{
            margin-left:0px;
            padding:3px 10px;
            min-width: 80px;
            margin-bottom:20px;
        }

        .notification_row{
            padding:40px 15px 10px 15px;
            background: #fff;
            margin-top: 30px;
            border-radius: 10px;
        }

        .el-select{
            width:100%;
        }

        .article_user_name,.article_created_date{
            padding-left:30px;
        }

        .article_created_date{
            color:#999;
            font-size:10px;
        }

        .sort_article_buttons{
            margin-bottom:10px;
        }

        .sort_article_buttons .createdate_btn{
            margin-right:10px;
        }

        @media(min-width:1024px) {
            .left_menu {
                float: left;
                width: 200px;
            }

            .right_content {
                float: left;
                width: calc(100% - 200px);
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        @media(max-width:767px) {
            .el-dialog {
                width: 90% !important;
                margin-left: auto;
                margin-right: auto;
            }

            .el-message-box {
                width: auto !important;
            }

            .right_content {
                padding-left: 15px;
                padding-right: 15px;
            }

            .add_role_btn {
                max-width: 40%;
            }

            .article_user_name,.article_created_date{
                padding-left:10px;
            }

            .xs-flex-reverse{
                display: flex; 
                flex-direction: column-reverse;
            }

            .article_right_section{
                margin-bottom:30px;
            }
        }

        .el-table--enable-row-hover .el-table__body tr:hover>td {
            background-color: #ddd;
        }

        .col-md-6 {
            padding-left: 0px !important;
            padding-right: 0px !important;
            flex: 0 !important;
            display: inline-block;
        }

        .col-xl,
        .col-xl-auto,
        .col-xl-12,
        .col-xl-11,
        .col-xl-10,
        .col-xl-9,
        .col-xl-8,
        .col-xl-7,
        .col-xl-6,
        .col-xl-5,
        .col-xl-4,
        .col-xl-3,
        .col-xl-2,
        .col-xl-1,
        .col-lg,
        .col-lg-auto,
        .col-lg-12,
        .col-lg-11,
        .col-lg-10,
        .col-lg-9,
        .col-lg-8,
        .col-lg-7,
        .col-lg-6,
        .col-lg-5,
        .col-lg-4,
        .col-lg-3,
        .col-lg-2,
        .col-lg-1,
        .col-md,
        .col-md-auto,
        .col-md-12,
        .col-md-11,
        .col-md-10,
        .col-md-9,
        .col-md-8,
        .col-md-7,
        .col-md-6,
        .col-md-5,
        .col-md-4,
        .col-md-3,
        .col-md-2,
        .col-md-1,
        .col-sm,
        .col-sm-auto,
        .col-sm-12,
        .col-sm-11,
        .col-sm-10,
        .col-sm-9,
        .col-sm-8,
        .col-sm-7,
        .col-sm-6,
        .col-sm-5,
        .col-sm-4,
        .col-sm-3,
        .col-sm-2,
        .col-sm-1,
        .col,
        .col-auto,
        .col-12,
        .col-11,
        .col-10,
        .col-9,
        .col-8,
        .col-7,
        .col-6,
        .col-5,
        .col-4,
        .col-3,
        .col-2,
        .col-1 {
            position: relative;
            width: 100%;
            padding-right: 0px;
            padding-left: 0px;
        }

        .btn.dropdown-toggle {
            margin-right: 40px;
        }

        .main_content_row {
            padding-bottom: 80px;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 10px 0px;
        }

        /*
        #list_table_wrapper .col-md-6{
            padding-left:15px;
            padding-right:15px;
        }*/

        .add_article_icon{
            margin-right:30px;
            color:#fff;
            cursor: pointer;
        }

        .article_detail_container{
            margin-top:30px;
            max-width:60%;
        }

        
    </style>
</head>

<body>
    <div id="app">
        <main class="">
            @yield("header")
            <div class="row">
                @foreach (['success', 'danger', 'warning'] as $info)
                    @if (session()->has($info))
                        <message-component message="{{ session()->get($info) }}" status="{{ $info }}">
                        </message-component>
                    @endif

                @endforeach
            </div>
            @yield('carousel')
            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
           @yield("js");
        });

    </script>
</body>

</html>
