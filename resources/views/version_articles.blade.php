<style>
    .content.init {
        height: 150px;
        overflow: hidden;
    }

    .hidden {
        display: none;
    }

    .read_more {
        margin-bottom: 10px;
    }

    .article_footer_paging {
        left: 50%;
        position: absolute;
        transform: translate(-50%, 10px);
    }

    .card {
        position: relative;
    }

    .card .list_bottom {
        position: absolute;
        bottom: 10px;
    }

    .card-body {
        padding-bottom: 40px !important;
    }

    .list_bottom>div {
        display: inline-block;
    }

    .article_right_section {
        padding-left: 30px !important;
    }

    .badge.m-badge {
        margin-bottom: 10px;
        padding: 0.85em 0.8em;
    }

    .badge-active{
        background:#343a40cf;
        color:#fff !important;
    } 

    @media(max-width:767px) {
        .article_right_section {
            padding-left: 0px !important;
        }
    }

</style>
@extends('layouts.app')

@section('carousel')
    <div class="mb-m">
        <swiper-component></swiper-component>
    </div>
@endsection
@include("layouts.header")
@section('content')
    <div class="row mb-l">
        <div class="container home">
            <div class="row xs-flex-reverse">
                <div class="article_left_section col-md-8 btn-primarycol-sm-6 col-xs-12">
                    <div id="accordion">
                        @foreach ($articles as $article)
                            <div class="card" style="margin-bottom:20px;">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            {{ $article->title }}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="content init">
                                            <div class="read_more" style="cursor:pointer">
                                                阅读更多 <i class="el-icon-arrow-down"></i>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <div>
                                                <?php echo html_entity_decode($article->content); ?>
                                                <div class="hidden open_up" style="cursor:pointer">
                                                    收起 <i class="el-icon-arrow-up"></i>
                                                </div>
                                            </div>
                                            <div class="list_bottom">
                                                <div>
                                                    <like-component 
                                                        :like_num="{{ $article->count_likes }}"
                                                        :article_id="{{ $article->id }}"
                                                        :is_login="{{Auth::check() == false ? 0 : true}}"
                                                    >
                                                    </like-component>
                                                </div>
                                                @can('update', $article)
                                                    <div>
                                                        <article-component 
                                                            type="edit" 
                                                            title="提示信息" 
                                                            :fields="
                                                                [
                                                                    {'name':'title','type':'text','ph':'请输入文章标题',val:'{{ $article->title }}'},
                                                                    {'name':'tags','type':'search','ph':'','request_url':'/',val:'{{ $article->tags }}'},
                                                                    {'name':'content','type':'editor','ph':'',val:'{{ $article->content }}'}
                                                                ]" 
                                                            msg="更新成功" 
                                                            status="success"
                                                            request_url="/articles_update/{{ $article->id }}"
                                                            redirect_url="/articles">
                                                        </article-component>
                                                    </div>
                                                @endcan

                                                @can('delete', $article)
                                                    <div>
                                                        <comfirm-component request_url="{{ route('article.del', $article) }}"
                                                            :small_size="true">
                                                        </comfirm-component>
                                                    </div>
                                                @endcan

                                                <div class="article_user_name">
                                                    {{$article->user->name}}
                                                </div>
                                                <div class="article_created_date">
                                                    {{$article->created_at}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="article_footer_paging"> {{ $articles->links() }}</div>
                </div>
                <div class="article_right_section col-md-4 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-header">相关标签</div>
                        <div class="card-body">
                            @foreach ($tags as $tag)
                                @if ($tag->article_count > 0)
                                    <a  href="/tag_articles/{{ $tag->id }}"
                                        class="badge m-badge 
                                        <?=$tag->id == request()->route("tag") ? " badge-active":" badge-primary"?>">
                                    {{ $tag->name }}({{ $tag->article_count }})</a>
                                @endif
                            @endforeach
                            <a href="/articles/" class="badge m-badge
                                <?=request()->route("tag") ? "badge-primary" : "badge-active"?>
                            ">所有</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('js')
    $(".card-body img").css("width","100%");
    
    $(".read_more").click(function(){
        $(this).parent().removeClass("init");
        $(this).parent().find(".open_up").removeClass("hidden");
        $(this).addClass("hidden");
    });

    $(".open_up").click(function(){
        $(this).parent().parent().addClass("init");
        $(this).parent().parent().find(".read_more").removeClass("hidden");
    });

    var perfEntries = performance.getEntriesByType("navigation");
    for (var i = 0; i < perfEntries.length; i++) { if(perfEntries[i].type==="back_forward" ){ window.location.reload(); } }
@endsection 
@endsection
