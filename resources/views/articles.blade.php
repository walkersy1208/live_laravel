@extends('layouts.app')
@section('carousel')
    <div class="mb-m">
        <!-- <carousel-component></carousel-component> -->
        <swiper-component></swiper-component>
    </div>
@endsection
@include("layouts.header")
@section('content')
    <div class="row mb-l">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>Articles List</h2>
                </div>
                <div class="card-body">
                    <table class="table table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th class="hidden-xs-down">Create data</th>
                                @if (Auth::check())
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td scope="row">
                                        <a class="article_link" href="{{ route('article.show', $article) }}"
                                            target="_blank">
                                            {{ $article->title }}
                                        </a>
                                    </td>
                                    <td>{{ $article->user->name }} </td>
                                    <td class="hidden-xs-down">{{ $article->created_at }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <!-- <button type="button" class="btn btn-primary">
                                                    <a href="{{ route('article.show', $article) }}" style="color:#fff;">
                                                        Detail
                                                    </a>
                                                </button> -->


                                            @can('update', $article)
                                                <!-- <button type="button" class="btn btn-success">
                                                            <a href="{{ route('article.edit', $article) }}" style="color:#fff;">Edit
                                                            </a> 
                                                        </button> -->
                                                           
                                                <article-component type="edit" title="提示信息" :fields="
                                                            [
                                                                {'name':'title','type':'text','ph':'请输入文章标题',val:'{{ $article->title }}'},
                                                                {'name':'tags','type':'search','ph':'','request_url':'/',val:'{{$article->tags}}'},
                                                                {'name':'content','type':'editor','ph':'',val:'{{ $article->content }}'}
                                                            ]" msg="更新成功" status="success"
                                                    request_url="/articles_update/{{ $article->id }}" redirect_url="/articles">

                                                </article-component>

                                            @endcan

                                            @can('delete', $article)
                                                <button type="button" class="btn btn-danger">
                                                    <comfirm-component request_url="{{ route('article.del', $article) }}">
                                                    </comfirm-component>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
@section('js')
    //检查浏览器是否有点击后退按钮
    var perfEntries = performance.getEntriesByType("navigation");
    for (var i = 0; i < perfEntries.length; i++) { if(perfEntries[i].type==="back_forward" ){ window.location.reload(); } }
        @endsection @endsection
