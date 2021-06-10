@extends('layouts.app')
@section('content')
    <div class="row" style="margin-top:30px;">
        <div>
            <?php 
                $redirect_url = "";
                $cms_index_page = "/api/carousel/";
                if(strpos(url()->current(),"/api/login")!==false){
                    $redirect_url  = $cms_index_page;
                }

                //if((strpos(url()->current(),"/api/login")!==false) && (strpos(url()->previous(),"/api/login") !== false) ){
                    // if(strpos(url()->previous(),"/api/login") !== false){
                    //     $redirect_url = $cms_index_page;
                    // }else{ 
                    //     dd(url()->previous());
                    //     $redirect_url = url()->previous();
                    // }
                    //$redirect_url = $cms_index_page;
                    
                    //$redirect_url = $cms_index_page;

                // }else{

                //     $redirect_url = url()->previous();
                // }

                


                // if(strpos(url()->current(),"/api/")!== false){
                //     if(strpos(url()->previous(),"/api/" )!== false ){
                //         if(strpos(url()->previous(),"/api/login" )!== false){
                //             $redirect_url = $cms_index_page;
                //         }else{
                //             $redirect_url = url()->previous();
                //         }
                //     }else{
                //         $redirect_url = $cms_index_page;
                //     }
                // }else{

                // }
            ?>
            
            <modal-component title="提示信息" :need_token_url=true :is_input=true :fields="[
                  {'name':'name','type':'text','ph':'请输入管理员名称','vm':'name'},
                  {'name':'password','type':'password','ph':'请输入管理员密码','vm':'desc'},
              ]" msg="登录成功" status="success" :trigger="true" request_url="/api/login" redirect_url="{{$redirect_url}}"  >
            </modal-component>
        </div>
    </div>
@section('js')

@endsection
@endsection
