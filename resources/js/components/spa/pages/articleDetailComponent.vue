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
        <div class="container xs-flex-reverse">
                <div  class="article_left_section col-md-12 col-xs-12"
                >
                    <div id="accordion">
                        <transition-group name="fade" v-if="articles">
                            <div class="card"  v-for="(item, index) in articles" :key="index+1" style="margin-bottom:30px;">
                                <div class="card-header" >
                                    <h5 class="mb-0">
                                        <button
                                            class="btn btn-link"
                                            data-toggle="collapse"
                                            aria-expanded="true"
                                            
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
                                        <div class="content">
                                            
                                            <div style="clear:both;"></div>
                                            <div v-html="item.content" class="content"></div>
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
            showHidden:true,
            article_id:0,
        };
    },


  
   
    methods:{
        async getArticleDetail(id){
            let submit_data = {
                "article_id":this.article_id
            };
            let config = {};
            let url = '/spa/article_detail';
            let res = await axios.post(url, submit_data,config);
            let {status,data} = res;
            if(status== "200" && data.code == "0"){
               this.articles.push(data.articles);
               //this.articles = data.articles;
            }
        },

        handleLikeLogin(){
            console.log(this.$parent.$refs.headerCo);
            //this.$parent.$refs.headerCom.openLoginForm();
        },

        handle_update_record(){
           this.$router.go(0);
        },
    },

    created(){
        if(localStorage.getItem('passport_token')){
            this.$store.dispatch("getUserInfo");
        }
    },
    
    mounted() {
        if(this.$route.params.id){
           this.article_id = this.$route.params.id;
           this.getArticleDetail(this.article_id);
        }
    },
};
</script>
