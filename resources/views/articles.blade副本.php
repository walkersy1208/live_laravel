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
                                @if(Auth::check())
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articles as $article)
                                <tr>
                                    <td scope="row">
                                        <a class="article_link" href="{{ route('article.show', $article) }}" target="_blank">
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
                                           

                                            @can("update",$article)
                                                <button type="button" class="btn btn-success">
                                                    <a href="{{ route('article.edit', $article) }}" style="color:#fff;">Edit
                                                    </a>
                                                </button>
                                            @endcan
                                             <!-- <a href="{{ route('article.del', $article) }}" style="color:#fff;">Delete -->

                                            @can("delete",$article)
                                                <button type="button" class="btn btn-danger">
                                                    <comfirm-component url="{{ route('article.del', $article) }}"></comfirm-component>
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
@endsection
