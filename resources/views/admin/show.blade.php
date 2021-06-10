@extends('layouts.app')
@section('content')
    <div style="clear:both;"></div>
    <div class="row">
        <div class="container">
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
                        <td>Done</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
   
    @section('js')
        $("#list_table").DataTable({ 
            "pageLength": 5

        });  
    @endsection
@endsection