@extends('layouts.app')
@include("admin.header")
@section('content')
    <div style="clear:both;"></div>
    <div class="row main_content_row">
        <div class="left_menu">
            <menu-component
                :current_url="'{{request()->path()}}'"
            >
            </menu-component>
        </div>
        <div class="right_content">
            <div>
                <?php 
                    //$token string
                    $url_params = $_SERVER['QUERY_STRING'];
                ?>
                @component('api.breadcrumb', [
                    'data' => [
                        '首页' => '/api/showAdmin?' . $url_params,
                        '轮播器列表' => '/api/articles_review?' . $url_params,
                    ],
                ])
                @endcomponent
            </div>
            <div style="clear:both;"></div>
            <table id="list_table" class="table table-striped border">
                <thead>
                    <tr style="font-weight:bold;font-size:20px">
                        <th>Article Title</th>
                        <th>Create Date</th>
                        <th>Status</th>
                        <th>Select</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_articles as $articles)
                    <tr article_id = "{{$articles->id}}"  priority="{{$articles->display_priority}}" >
                            <td>{{ mb_strlen($articles['title'],'utf8')>14 ?  mb_substr($articles['title'], 0,20, 'utf-8') . '...' : $articles['title']}}</td>
                            <td>{{ $articles['created_at'] }}</td>

                            <td>
                                <span class="badge badge-{{ $articles['status_tag_class'] }}">
                                    {{ (string) $articles['status_mess'] }}
                                </span>
                            </td>
                            <td>
                                <select-component 
                                    aid="{{ $articles['id'] }}" 
                                    :all_select_data="[
                                        {label:'Approval',value:'1'},
                                        {label:'Pending',value:'0'},
                                        {label:'Disapproval',value:'-1'},
                                    ]" :data_status="{{ $articles['status'] }}">
                                </select-component>
                            </td>
                            <td>
                                <div class="btn-group"  role="group">
                                    <div>
                                       
                                        <article-component
                                            type="edit" 
                                            title="提示信息" 
                                            :fields="
                                            [
                                                {'name':'title','type':'text','ph':'请输入文章标题','val':'{{$articles->title}}'},
                                                {'name':'tags','type':'search','ph':'','request_url':'/','val':'{{$articles->tags}}'},
                                                {'name':'content','type':'editor','ph':'',val:'{{$articles->content}}'}
                                            ]" 
                                            msg="更新成功" 
                                            status="success"
                                            request_url="/api/articles_update/{{$articles->id}}"
                                            redirect_url=""
                                            
                                        >
                                        </article-component> 
                                    </div>
                                    <div>
                                        <comfirm-component 
                                            request_url="/api/delete_article/{{$articles->id}}"
                                        >
                                        </comfirm-component>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>

@section('js')
    $("#list_table").DataTable({
        "order": ['3','asc'],
        "bPaginate" : true,  
        //"bInfo" : false,
    });

    

    const tbody = document.querySelector(
        "tbody"
    );

    let tr_el = $("#list_table tbody tr");
            window.Sortable.Sortable.create(tbody,
            {
                animation: 250, 
                onEnd({ newIndex, oldIndex }) {
                   
                    let newIndex_id =  $(tr_el[newIndex]).attr("article_id");
                    let newIndex_display_priority = $(tr_el[newIndex]).attr("priority");

                    let oldIndex_id =  $(tr_el[oldIndex]).attr("article_id");
                    let oldIndex_display_priority = $(tr_el[oldIndex]).attr("priority");
                    
                    //change display_priority
                    $(tr_el[newIndex]).attr("priority",oldIndex_display_priority);
                    $(tr_el[oldIndex]).attr("priority",newIndex_display_priority);

                   
                    tmp=[
                        {"id":oldIndex_id,"priority":newIndex_display_priority},
                        {"id":newIndex_id,"priority":oldIndex_display_priority}
                    ]

                    let submit_data = {
                        "sort_data":tmp
                    };
                        
                    let url = "/api/sort_table_articles";
                   
                   
                    axios.post(url, submit_data).then(res => {
                        let { status, data } = res;
                        if (status == "200") {
                            if(data.code === "0"){
                                
                                /*setTimeout(function(){
                                    window.location.reload();
                                }, 700);*/
                            }
                        }
                    });
                
                }
            }
           
            );
        
   
@endsection
@endsection
