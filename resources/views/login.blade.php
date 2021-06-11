@extends('layouts.app')
@section('content')
<div id="login_form_wrapper" style=" background-image: url('/images/1120.png');" >
   <form action="/check" class="login_form" method="post" style="width:80%;margin:0 auto">
        {{csrf_field()}}  
        <div class="form-group">
          <input type="text" name="email" class="form-control" value = "{{old('email')}}" placeholder="Enter Email" aria-describedby="helpId">
          @error('email')
            <span class="feedback_msg">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="form-group">
          <input type="password" name="password"  value = "{{old('password')}}" class="form-control" placeholder="Enter Password" aria-describedby="helpId">
          @error('password')
            <span class="feedback_msg">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <button type="submit" class="btn   btn-secondary" >确定</button>
    </form>
</div>
@endsection
