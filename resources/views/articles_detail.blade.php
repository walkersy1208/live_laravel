@extends('layouts.app')
@include("layouts.header")
@section('content')
<div class="row">
    <div class="container article_detail_container">
        <div class="card">
          <div class="card-header">
            <div class="">
              <div><h3>{{$article_detail->user->name}}</h3></div>
              <div>{{$article_detail->created_at}}</div>
              <div>
                <like-component article_title="{{$article_detail->title}}" :is_liked={{$is_liked ? "1":"0"}}  :article_id={{$article_detail->id}}></like-component>
              </div>
            </div>
          </div>
            <div class="card-body">
              <div><h3>{{$article_detail->title}}</h3></div>
              <div id="article_content">
                  <?php echo html_entity_decode($article_detail->content) ?>
              </div>
          </div>
          <div class="card-footer">
          </div>
        </div>
    </div>     
</div>
@section('js')
    $("#article_content").find("img").css("width","100%");
@endsection
@endsection
