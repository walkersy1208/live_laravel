@extends('layouts.app')
@include("layouts.header")

@section('content')
    <div class="container">
        <!-- <div class="row justify-content-center">
            <div class="row notification_row">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Auth::user()->unreadNotifications as $notification)
                            <tr>
                                <td class="hidden-xs-down">
                                    {{ $notification->data['name'] }} 点赞了你的文章{{ $notification->data['article_title'] }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" nid="{{$notification->id}}" id="mark_as_read_btn" class="btn btn-primary">
                                            标记为已读
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> -->
        
        <notificationlist-component
            :message = "{{json_encode($page->items())}}"
            request_url = "/mark_read"
            redirect_url="reload"
            status='success'
            msg="标记成功"
        >
        </notificationlist-component>
        <div class="page_links" style="margin-top:30px;">
            {{$page->links()}}
        </div>
    </div>
   
    @section('js')
        /*$("#mark_as_read_btn").click(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            let notify_id = $(this).attr("nid");
            
            $.post("/mark_read",{nid:notify_id},function(result){
                if(res){

                }
            });
        });*/
    @endsection
@endsection
