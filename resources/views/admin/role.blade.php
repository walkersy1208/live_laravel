@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="container">

            <div>
                <modal-component title="提示信息" :is_input=true :fields="[
                        {'name':'name','type':'text','ph':'请输入角色名称','vm':'name'},
                        {'name':'desc','type':'text','ph':'请输入角色描述','vm':'desc'},
                    ]" msg="添加成功" status="success" request_url="/admin/add_role">
                    <div slot="add_role_btn" class="btn btn-primary">添加角色</div>
                </modal-component>
            </div>


            <table class="table table-striped border">
                <thead>
                    <tr style="font-weight:bold;font-size:20px">
                        <th>Admin Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                <div class="" role="group">
                                    <div type="button" class="btn btn-success ">
                                        <edit-component title="提示信息" :is_input=true :fields="[
                                            {'name':'name','type':'text','ph':'请输入角色名称','vm':'name','val':'{{ $role->name }}' },
                                            {'name':'desc','type':'text','ph':'请输入角色描述','vm':'desc','val':'{{ $role->desc }}' },
                                        ]" msg="修改成功" status="success" request_url="/admin/edit_role/{{ $role->id }}">
                                        </edit-component>
                                    </div>
                                    <button type="button" class="btn btn-danger">
                                        <comfirm-component request_url="/admin/delete_role/{{ $role->id }}" ></comfirm-component>
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
