@extends('layouts.app')
@section('content')
<div class="container">
    <notify-component></notify-component>
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            总消息{{Auth::user()->Notifications()->count()}}
            未读消息{{Auth::user()->unreadNotifications()->count()}}

            @foreach(Auth::user()->unreadNotifications as $notification)
                {{$notification->markAsRead()}}
                {{$notification->data["name"]}} 关注了你的文章 《 {{$notification->data["article_title"]}} 》
            @endforeach
        </div>
    </div>
</div>
@endsection
