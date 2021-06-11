<template>
    <div> 
        <div class="btn-group sort_article_buttons" role="group" aria-label="Basic mixed styles example">
            <button type="button" class="btn  btn-sm createdate_btn" :class="[date_btn_active ? 'btn-primary' :'btn-secondary']" @click="article_order_date()"><i class="el-icon-sort"></i>日期</button>
            <button type="button" class="btn  btn-sm hot_btn" :class="[hot_btn_active ? 'btn-primary' :'btn-secondary']" @click="article_order_hot()"><i class="el-icon-sunny"></i>热门</button>
        </div>
    </div>
</template>
<style scoped>
    .sort_article_buttons i{
        display: inline-block;
        margin-right:8px;
    }
</style>
<script>
    
    export default {
        props:["spa_page"],
        data(){
            return {
                "date_btn_active":true,
                "hot_btn_active":false,
            };
        },
        methods:{
            article_order_date(){
                if(!this.spa_page){
                    window.location.href="/article_sort/date";
                }
            },

            article_order_hot(){
                if(!this.spa_page){
                   window.location.href="/article_sort/hot";
                }   
            }
        },
        mounted() {
            if(window.location.href.indexOf("hot")!="-1"){
                this.hot_btn_active = true;
                this.date_btn_active = false;
            }else{
                this.hot_btn_active = false;
                this.date_btn_active = true;
                
            }
        }
    }
</script>
