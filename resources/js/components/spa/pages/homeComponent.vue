<template>
    <div>
        <spaheader-component 
            ref="headerCom"
            :is_login="auth"
        >
        </spaheader-component>
        <div class="mb-m">
            <swiper-component></swiper-component>
        </div>
        <div class="container">
        <div class="row">
            <sortbtn-component
                :spa_page="true"
                @sort_article="sort"
            >
            </sortbtn-component>
        </div>
        <div class="row xs-flex-reverse" :key="time">
                <div  class="article_left_section col-md-8 btn-primarycol-sm-6 col-xs-12"
                >
                    <div id="accordion">
                        <transition-group name="fade" v-if="articles">
                            <div class="card"  v-for="(item, index) in articles" :key="index+1" style="margin-bottom:30px;">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button
                                            class="btn btn-link"
                                            data-toggle="collapse"
                                            aria-expanded="true"
                                            data-target="#collapseOne"
                                            aria-controls="collapseOne"
                                        >
                                            {{item.title}}
                                        </button>
                                    </h5>
                                </div>

                                <div
                                    id="collapseOne"
                                    class="collapse show"
                                    aria-labelledby="headingOne"
                                    data-parent="#accordion"
                                >
                                    <div class="card-body">
                                        <div class="content" :class="{'init':showReadMore}">
                                            <div class="read_more" style="cursor:pointer" @click="readMore($event,index)">
                                                阅读更多<i class="el-icon-arrow-down"></i>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <div v-html="item.content" class="content"></div>
                                            <div>
                                                <div class="open_up" @click="openUp($event,index)" style="cursor:pointer">
                                                    收起 <i class="el-icon-arrow-up"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="list_bottom">
                                                <div>
                                                    <spalike-component
                                                    :article_id="item.id"
                                                    :is_login="auth"
                                                    :likes_num.sync="item.count_likes"
                                                    :showLikeCom = "showHidden"
                                                    @login = "handleLikeLogin"
                                                    ref="likeCom"
                                                    >
                                                    </spalike-component>
                                                </div>
                                                    <div  v-if="auth && (item.user_id == auth_id)">
                                                        <article-component
                                                            @update_record='handle_update_record'
                                                            type="edit" 
                                                            title="提示信息" 
                                                            :fields="
                                                            [
                                                                {'name':'title','type':'text','ph':'请输入文章标题','val':`${item.title}`},
                                                                {'name':'tags','type':'search','ph':'','request_url':'/','val':`${JSON.stringify(item.tags)}`},
                                                                {'name':'content','type':'editor','ph':'',val:`${item.content}`}
                                                            ]" 
                                                            msg="更新成功" 
                                                            status="success"
                                                            :request_url="'/spa/articles_update/'+item.id"
                                                            redirect_url="/spa/index"
                                                        >
                                                        </article-component> 
                                                    </div>
                                            
                                                    <div  v-if="auth && (item.user_id == auth_id)">
                                                        <comfirm-component  
                                                            :request_url="'articles_del/'+item.id"
                                                            :small_size="true"
                                                            :del_id = "item.id"
                                                        >
                                                        </comfirm-component>
                                                    </div>
                                                    <div class="article_user_name" >
                                                        {{item.user.name}}
                                                    </div>
                                                    <div class="article_created_date" >
                                                        {{item.created_at}}
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </div>
                </div>
                 <div class="article_right_section col-md-4 btn-primarycol-sm-6 col-xs-12 " >
                    <div class="card">
                        <div class="card-header">相关标签3</div>
                        <div class="card-body">
                            <a  
                                style="cursor:pointer;" 
                                @click="handleTag(tag.id,index2)"  v-if="tag.article_count>0"  v-for="(tag,index2) in related_tags" :key=index2
                                class="badge m-badge " 
                                :class="[curr_tag_index == index2 ? 'badge-active': 'badge-primary' ]">
                               
                                {{ tag.name }}({{tag.article_count }})
                            </a>
                            <a style="cursor:pointer;" @click="handleTag()" class="badge m-badge " :class="[tag_default_choose ? 'badge-active': 'badge-primary' ]">所有</a>
                        </div>
                    </div>
                </div>
        </div>
        <div style="margin-top:30px;">
            <el-pagination 
                background
                layout="prev, pager, next"
                :total="total_nums"
                :page-size="per_page"
                :current-page.sync="cur_page"
                @current-change ="page_change"
            >
            </el-pagination>
        </div>
        </div>
    </div>
</template>
<style>
   .spa .card-body .content img{
      width:100%;
   }

   .spa .card{
       margin-bottom:10px;
   }

   .content.init{
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
        margin-right:10px;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active, 2.1.8 版本以下 */ {
        opacity: 0
    }

    .badge-active{
        background:#343a40cf;
        color:#fff !important;
    }

    img{
        width:100%;
    }

    .article_user_name,.article_created_date{
        padding-left:30px;
    }

    .article_created_date{
        color:#999;
        font-size:10px;
    }


    @media(max-width:767px) {
        .article_right_section {
            padding-left: 0px !important;
        }

        .xs-flex-reverse{
           display: flex; 
           flex-direction: column-reverse;
        }

        .article_user_name,.article_created_date{
            padding-left:10px;
        }

        .article_right_section{
            margin-bottom:30px;
        }

        .el-pagination{
            text-align: center;
        }
    }


</style>
<script>
    
 import articlecomponent from '../../../components/AddArticleComponent.vue';
 import {mapState} from 'vuex';   
 export default {
    
    components: {
        articlecomponent,
    },

    computed:{
        ...mapState({
            auth: (state) => state.AuthUser.auth,
            auth_id: (state) => state.AuthUser.id,
        }),
    },

    data() { 
        return {
            articles:[],
            related_tags:[],
            total_nums:0,
            per_page:0,
            cur_page:0,
            page_type:"",
            tag_id:"",
            showHidden:true,
            tag_active:false,
            curr_tag_index:-1,
            tag_default_choose:true,
            time: '',
            showReadMore:true,
            open_up:false,
            sortBy:"display_priority",
        };
    },


    // watch: {
    //     'articles'(newVal, oldVal) {
    //         /*return this.handleTag()*/;
    //         if(oldVal!=""){
    //            //this.handleTag();
    //         }
    //     }
    // },
   
    methods:{
        sort(condition){
            let url = "/spa/get_articles/";
            this.sort_article(url,condition);
        },

        async sort_article(url,condition){
            this.sortBy = condition;
            let submit_data = {
                "sortBy": condition,
            }
            let config = {};
            
            let res = await axios.post(url, submit_data,config);
            let {status,data} = res;
            if(status == "200" && data.code!="-1"){
                this.articles = [];
                this.setPageData(data);
            }
        },

        readMore(e,index){ 
            if(e.target.parentNode.parentNode.className.indexOf("init")!=-1){
                e.target.parentNode.parentNode.className="content";
            }
        },

        openUp(e,index){
            if(e.target.parentNode.parentNode.parentNode.className.indexOf("content")!=-1){
                e.target.parentNode.parentNode.parentNode.className="content init";
            }
        },

        handle_update_record(){
           this.$router.go(0);
        },

        reload () {
            this.time = new Date().getTime()
        },

        handleLikeLogin(){
            //this.$parent.$refs.headerCom.openLoginForm();
            this.$parent.$refs.headerCom.openLoginForm();
        },

        setPageData(data){
            this.articles = data.articles.data;
            this.related_tags = data.tags;
            this.total_nums = data.articles.total;
            this.per_page = data.articles.per_page;
            this.cur_page = data.articles.current_page;
        },
        
        /*checkUserLike(){
            let _this = this;
            let data = {};
            data.article_id = this.art_id;
            axios.post("/spa/checkUserLike", data).then(res => {
                let { status, data } = res;
                if (status == "200") {
                    
                    if(data.code =="0"){ 
                        if(data.user_is_liked){
                            _this.user_is_liked = true;
                        }else{
                            _this.user_is_liked = false;
                        }

                        this.user_is_liked = _this.user_is_liked;
                        
                    }
                } else {
                }
            });
        },*/

        async page_change(page){
            
            //this.showHidden  = false
            this.articles = [];
            this.cur_page = page;
           
            let submit_data = {
               "page":this.cur_page,
               "sortBy":this.sortBy,
            }

            let config = {};
            let url = "";

            if(this.page_type == "tag"){ 
                submit_data.tag_id = this.tag_id;
               
                url = '/spa/tag_articles';
            }else{  
                url = '/spa/get_articles';
            }

            let res = await axios.post(url, submit_data,config);
            let _this = this;
            let {status,data} = res;
            if(status == "200"){ 
                this.setPageData(data);
                    if(_this.auth){ 
                        this.$nextTick(function () {
                            for(let i in this.$refs.likeCom){
                                this.$refs.likeCom[i].checkUserLike();
                            }
                        })
                    }
            }
        },

        async initData(){
            let submit_data = {};
            let config = {};
            let url = '/spa/get_articles';
            let res = await axios.post(url, submit_data,config);
            let {status,data} = res;
            //console.log(data);
            if(status == "200"){
                this.setPageData(data);
            }
        },

        
        async handleTag(tag_id,index){
                
                this.tag_active = true;
                let submit_data = {
                    "sortBy":this.sortBy,
                };

                if(tag_id){

                    submit_data.tag_id = tag_id;
                    this.tag_id = tag_id;
                    this.page_type = "tag";
                    this.tag_default_choose = false;
                    this.curr_tag_index = index;

                }else{

                    this.page_type = "";
                    this.tag_default_choose = true;
                    this.curr_tag_index = "-1";
                }
                
                let config = {};
                let url = '/spa/tag_articles';
                let res = await axios.post(url, submit_data,config);
                let {status,data} = res;
                if(status == "200"){ 
                    this.setPageData(data);
                    if(this.auth){ 
                        this.$nextTick(function () {
                            for(let i in this.$refs.likeCom){
                                this.$refs.likeCom[i].checkUserLike();
                                this.showHidden = true;
                            }
                        })
                    }
                }
            }
       
    },
    
    created(){
        if(localStorage.getItem('passport_token')){
            this.$store.dispatch("getUserInfo");
        }
    },

    async mounted() {
       this.initData();
    },
};
</script>
