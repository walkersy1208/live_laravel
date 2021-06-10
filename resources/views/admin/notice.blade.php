@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="container">

            <div>
                <modal-component title="提示信息" :is_input=true :fields="[
                        {'name':'title','type':'text','ph':'请输入消息名称','vm':'title'},
                        {'name':'content','type':'text','ph':'请输入消息内容','vm':'content'},
                    ]" msg="添加成功" status="success" request_url="/admin/add_notice">
                    <div slot="add_role_btn" class="btn btn-primary">添加消息</div>
                </modal-component>
            </div>


            <table class="table table-striped border">
                <thead>
                    <tr style="font-weight:bold;font-size:20px">
                        <th>Notices</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_notices as $notice)
                        <tr>
                            <td>{{ $notice->title }}</td>
                            <td>
                                <div class="" role="group">
                                    <div type="button" class="btn btn-success ">
                                        <edit-component title="提示信息" :is_input=true :fields="[
                                            {'name':'title','type':'text','ph':'请输入消息名称','vm':'name','val':'{{ $notice->title }}' },
                                            {'name':'content','type':'text','ph':'请输入消息内容','vm':'desc','val':'{{ $notice->content }}' },
                                        ]" msg="修改成功" status="success" request_url="/admin/edit_notice/{{ $notice->id }}">
                                        </edit-component>
                                    </div>
                                    <button type="button" class="btn btn-danger">
                                        <comfirm-component request_url="/admin/delete_notice/{{ $notice->id }}" ></comfirm-component>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<script>
</script>
