@extends('layouts.app')
@section('content')
<div class="row" style="margin-top:30px;">
   <form action="/admin/checkLogin" class="login_form" method="post" style="width:80%;margin:0 auto">
        {{csrf_field()}}  
        <div class="form-group">
          <input type="text" name="name" id="" class="form-control" placeholder="Enter Name" aria-describedby="helpId">
          @error('name')
            <span class="feedback_msg">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="form-group">
          <input type="password" name="password" id="" class="form-control" placeholder="Enter Password" aria-describedby="helpId">
          @error('password')
            <span class="feedback_msg">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
