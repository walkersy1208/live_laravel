@extends('layouts.app')
@include("layouts.header")
@section('content')
    <div class="form-group">
    </div>
    <div class="row">
        <!-- <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>Articles List</h2>
                </div>
                <div class="card-body">
                    <form action="/articles_update/" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <tags-component aid="{{ $article->id}}"></tags-component>     
                        </div>     

                        <div>
                            <input type="hidden" value="{{ $article->id }}" name="article_id">
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" value="{{ $article->title }}" class="form-control"
                                placeholder="Enter Title" id="title" name="title">
                            @error('title')
                                <span class="feedback_msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" name="content" id="content" rows="3">{{ $article->content }}
                                </textarea>
                            @error('content')
                                <span class="feedback_msg">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div> -->

        <article-component
            title="提示信息"
            :fields="
            [
                {'name':'title','type':'text','ph':'请输入文章标题',val:'{{ $article->title }}'},
                {'name':'content','type':'editor','ph':'',val:'{{ $article->content }}'}
            ]" 
            msg="更新成功" 
            status="success"
            :trigger="true"
            request_url="/articles_update/{{$article->id}}" 
            redirect_url="/articles"
        >

        </article-component>

    </div>
@endsection
