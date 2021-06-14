<template>
    <div> 
        <div class="btn-group sort_article_buttons" role="group" aria-label="Basic mixed styles example">
            <button type="button" class="btn   createdate_btn" :class="[date_btn_active ? 'btn-primary' :'btn-secondary']" @click="article_order_date()"><i class="el-icon-sort"></i>日期</button>
            <button type="button" class="btn   hot_btn" :class="[hot_btn_active ? 'btn-primary' :'btn-secondary']" @click="article_order_hot()"><i class="el-icon-sunny"></i>热门</button>
            <div class="search_input">
                <input type="text" class="form-control" placeholder="请搜索" :value="clearInput? '':value" @keyup="search_change($event)" >
                <div class="icon_search"><i class="el-icon-search"></i></div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .sort_article_buttons i{
        display: inline-block;
        margin-right:8px;
    }

    .search_input{
        position: relative;
        left: 10px;
        display: inline-block;
    }

    .search_input input{
        border-radius: 10px;
        min-width:400px;
        position: relative;
    }

    .icon_search{
        position: absolute;
        font-size: 25px;
        color: #999;
        right:0px;
        top:5px;
    }

    @media(max-width:767px){
        .search_input{
            position: relative;
            left: 20px;
            display: inline-block;
           
        }

        .search_input input{
            max-width: 150px !important;
            min-width: auto;
        }
    }


</style>
<script>
    
    export default {
        props:["spa_page","clearInput"],
        data(){
            return {
                "date_btn_active":true,
                "hot_btn_active":false,
                "value":"",
               
            };
        },
        methods:{
            article_order_date(){
                if(!this.spa_page){
                    window.location.href="/article_sort/date";
                }else{
                    this.$emit("sort_article",'date');
                    this.hot_btn_active = false;
                    this.date_btn_active = true;
                }
            },

            search_change(e){
                if(e.target.value!=""){
                    this.value = e.target.value;
                    this.$emit("search",e.target.value);
                }
            },

            article_order_hot(){
                if(!this.spa_page){
                    window.location.href="/article_sort/hot";
                }else{
                    this.$emit("sort_article",'hot');
                    this.hot_btn_active = true;
                    this.date_btn_active = false;
                }
            }
        },
        mounted() {
            if(!this.spa_page){ 
                if(window.location.href.indexOf("hot")!="-1"){
                    this.hot_btn_active = true;
                    this.date_btn_active = false;
                }else{
                    this.hot_btn_active = false;
                    this.date_btn_active = true;
                }
            }
        }
    }
</script>
