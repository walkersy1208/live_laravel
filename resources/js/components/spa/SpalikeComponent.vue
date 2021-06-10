<template>
    <div v-if="showLikeCom">{{art_id}}
        <div class="like_warapper" style="cursor:pointer;" @click="handlelike()">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                v-if="!user_is_liked"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-heart"
                viewBox="0 0 16 16"
            >
                <path
                    d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"
                />
            </svg>
            <svg
                v-if="user_is_liked"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-heart-fill"
                viewBox="0 0 16 16"
            >
                <path
                    fill-rule="evenodd"
                    d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"
                />
            </svg><span class="like_num">{{ article_like_num=likes_num}}</span>
            
        </div>
    </div>
</template>
<style scoped>
    .like_num{
        display: inline-block;
        padding-left:5px;
    }
</style>
<script>
import {mapState} from 'vuex';   
export default {
    props: ["article_id", "is_liked","likes_num","is_login","showLikeCom"],

    data() {
        return {
            art_id: "",
            user_is_liked: false,
            article_like_num:0,

        };
    },

    
    computed:{
        ...mapState({
            auth: (state) => state.AuthUser.auth,
            auth_id: (state) => state.AuthUser.id,
        }),
    },
   

    methods: {
        checkUserLike(){
            let _this = this;
            let data = {};
            data.article_id = this.article_id;
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
        },

        handlelike() {
            if(!localStorage.getItem('passport_token')){
                this.$router.push({
                    path:'/spa/login'
                });
            }

            let _this = this;
            let data = {};
            if(!this.is_login){
               this.$emit('login');
               return false;
            }

            data.article_id = this.article_id;
            axios.post("/spa/articles_like", data).then(res => {
                let { status, data } = res;
                if (status == "200") {

                    if(data.code =="0"){ 
                        _this.user_is_liked = !_this.user_is_liked;
                        _this.article_like_num = data.count_likes;
                        _this.$emit('update:likes_num', _this.article_like_num);
                    }

                } else {

                }
            });
        }
    },

    mounted() {
        this.article_like_num = this.likes_num;
        if(this.is_login){
            this.checkUserLike();
        }
    }
};
</script>
