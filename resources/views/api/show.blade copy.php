@extends('layouts.app')
@section('content')
    <div style="clear:both;"></div>
    <div class="row">
        <div class="left_menu">
            <menu-component></menu-component>
        </div>
        <div class="right_content">
            <table id="list_table" class="table table-striped border">
                 <thead>
                     <tr style="font-weight:bold;font-size:20px">
                         <th>Admin Name</th>
                         <th>Action</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($admin_users as $user)
                     <tr>
                        <td>{{$user["name"]}}</td>
                        <td>
                            <td>
                                Action
                                <!-- <div class="" role="group">
                                    <div type="button" class="btn btn-success ">
                                        <edit-component title="提示信息" :is_input=true :fields="[
                                            {'name':'name','type':'text','ph':'请输入管理员名称','vm':'name','val':'{{ $user->name }}' },
                                            {'name':'password','type':'password','ph':'请输入管理员密码','vm':'desc','val':'' },
                                            {'name':'repassword','type':'password','ph':'请再次输入管理员密码','vm':'desc','val':'' },
                                            ]" msg="修改成功" status="success" request_url="/api/edit_role/{{ $user->id }}">
                                        </edit-component>
                                    </div>
                                    <button type="button" class="btn btn-danger">
                                        <comfirm-component request_url="/api/delete_role/{{ $user->id }}" ></comfirm-component>
                                    </button>
                                </div> -->
                            </td>   
                         </td>
                     </tr>
                     @endforeach
                 </tbody>
             </table>
        </div>
    </div>
   
    @section('js')
        $("#list_table").DataTable({ 
            //"pageLength": 5
        });  
    @endsection
@endsection