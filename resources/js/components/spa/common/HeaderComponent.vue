<template>  
    <div class="row nav_wrapper" style="width:100%;">
        <message-component
            v-if="success_response"
            :status="status"
            :message="message"
        ></message-component>
        <nav
            class="navbar navbar-expand-sm navbar-dark bg-dark"
            :class="{ bg_gray: gray_color }"
        >
            <a class="navbar-brand" href="/spa/index">Website</a>
            <button class="navbar-toggler d-lg-none" 
                type="button" 
                data-toggle="collapse" 
                data-target="#collapsibleNavId" 
                aria-controls="collapsibleNavId" 
                aria-expanded="false" 
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li style="display:none;"
                        class="nav-item active"
                        
                        v-if="!auth"
                    >
                        <a class="nav-link" href="#"  
                            >Register <span class="sr-only">(current)</span></a
                        >
                    </li>

                    <li
                        @click="openLoginForm"
                        class="nav-item"
                        v-if="!auth"
                        ref="login"
                    >
                        <a class="nav-link"  href="javascript:void(0)">Login</a>
                    </li>

                    <li class="nav-item" v-if="auth">
                        <a
                            class="nav-link"
                            href="javascript:void(0);"
                            @click="logout"

                            >Logout</a
                        >
                      
                    </li>
                </ul>

                <div
                    class="add_article_icon"
                    v-if="auth"
                    @click="add_articles"
                >
                    <i class="el-icon-edit-outline" style="font-size:22px;"></i>
                </div>

                <div class="user_info_wrapper" v-if="is_login && logout_url == undefined && username">
                    <div class="dropdown">
                        <button style="background:#343a40;border:none;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span style="font-size:20px;">
                                <i class="el-icon-user-solid"></i>
                            </span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">个人中心</a>
                            <a class="dropdown-item" href="#">{{username}}</a>
                            <a class="dropdown-item" href="#">Action</a>
                        </div>
                    </div>
                </div>

                <div class="notifications_wrapper" v-if="is_login && logout_url == undefined">
                    <div class="dropdown">
                        <button
                            style="background:#ccc"
                            type="button"
                            class="btn  dropdown-toggle"
                            data-toggle="dropdown"
                        >
                            <span
                                style="background:red;color:#fff;"
                                class="badge badge-light"
                                >{{ unread_notifications }}</span
                            >
                            Message
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" @click="read_notifications()">
                                <div
                                    style="background:red;color:#fff;"
                                    class="badge badge-light"
                                >
                                    {{ notification_num }}
                                </div>
                                All Message</a
                            >
                            <a class="dropdown-item" @click="unread_all_notifications()">
                                <div
                                    style="background:red;color:#fff;"
                                    class="badge badge-light"
                                >
                                    {{ unread_notifications }}
                                </div>
                                New Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <article-component
            ref="article_component"
            title="提示信息"
            :fields="[
                {
                    name: 'title',
                    type: 'text',
                    ph: '请输入文章标题',
                    vm: 'name'
                },
                {
                    name: 'tags',
                    type: 'search',
                    ph: '',
                    request_url: '/articles_tags_search'
                },
                { name: 'content', type: 'editor', ph: '' }
            ]"
            msg="添加成功"
            status="success"
            request_url="/spa/articles_add"
        >
        </article-component>
    </div>
</template>
<style>
.bg_gray {
    background: #606266 !important;
}

.nav_wrapper {
    height: 53px;
}

.nav_wrapper .navbar {
    position: fixed;
    left: 0px;
    top: 0px;
    z-index: 999;
}
.el-form-item__label {
    text-align: left;
}

.navbar {
    width: 100% !important;
}
.row {
    margin-left: 0px;
    margin-right: 0px;
}

@media (min-width: 768px) {
    .regForm_wrapper {
        max-width: 60%;
        margin: 0 auto;
    }
}
</style>
<script>
import {mapState} from 'vuex';
export default {
    
    props: [
        "is_login",
        "request_url",
        "gray_color",
        "logout_url"
    ],

    computed:{
        ...mapState({
            auth: (state) => state.AuthUser.auth,
            username: (state) => state.AuthUser.name,
        }),
    },

    data() {
        return {
            is_show: false,
            notification_num: 0,
            unread_notifications: 0,
            current_url: "",
            show_article_component: false,
            openLogin:false,
            success_response:false,
            status:'',
            message:'',
            notify_data:''
        };
    },

    mounted() {
        if(localStorage.getItem('passport_token')){
            this.checkUserNotifications();
        }
    },

    watch: {
        $route(to, from) {
           
            //console.log(to,from);
            //this.$router.go(0);
            // console.log(to.name);
            // console.log(from.name);
            // if(to.name == "login"){
            //     this.$refs.login.style.display = "none";
            // }else{
            //     this.$refs.login.style.display = "block";
            // }
        }
    },

    methods: {
        
        async checkUserNotifications(){
            let config = {};
            let submit_data = {};
            let url = '/spa/read_all_notifications/';
            let res = await axios.post(url, submit_data,config);
            let {status,data} = res;
            if(status == "200"){
                this.notify_data = data.page.data;
                if (this.notify_data != undefined) {
                    this.notification_num = this.notify_data.length;
                    for (let i = 0; i < this.notify_data.length; i++) {
                        if (!this.notify_data[i].read_at) {
                            this.unread_notifications += 1;
                        }
                    }
                }  
            }
        },

        read_notifications(){
            if(this.$route.path == '/spa/read_all_notifications'){
                return false;
            }
            this.$router.push({
               path:'/spa/read_all_notifications'
            });
        },

        unread_all_notifications(){
            if(this.$route.path == '/spa/read_notifications'){
                return false;
            }
            this.$router.push({
               path:'/spa/read_notifications'
            });
        },

        openLoginForm(){
            //modal open
            //this.$refs.loginForm.dialogVisible = true;
            this.$router.push({
                name:'login'
            });
        },

        add_articles() { 
            this.$refs.article_component.showDialog();
        },

        async logout() {
            if(localStorage.getItem('passport_token')){
                localStorage.removeItem('passport_token');
                let url = "/spa/logout";
                let submit_data = {};
                let config = {};
                let res = await axios.post(url, submit_data,config);
                let {status,data} = res;
                let _this = this;
                if(data.code == "0"){
                    _this.success_response = true;
                    _this.status = 'success';
                    _this.message = '退出成功';
                    _this.$store.dispatch("unsetUserAuth");
                    if(_this.$route.path == '/spa/index'){
                        _this.$router.go(0);
                    }else{
                        _this.$router.push({
                            path:'/spa/index'
                        });
                    }
                }
            }
        },

        checkUrl() {
            if (this.request_url != "") {
                return new URL(this.request_url).pathname;
            }
        }
    }
};
</script>


