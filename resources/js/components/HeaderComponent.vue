<template><!--<i class="fas fa-bars fa-1x">-->
    <div class="row nav_wrapper" style="width:100%;">
        <nav
            class="navbar navbar-expand-sm navbar-dark bg-dark"
            :class="{ bg_gray: gray_color }"
        >
            <a class="navbar-brand" href="/articles">Website</a>
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
                        @click="openRegForm"
                        v-if="checkUrl() != '/register' && !is_login"
                    >
                        <a class="nav-link" href="#"
                            >Register <span class="sr-only">(current)</span></a
                        >
                    </li>

                    <li
                        class="nav-item"
                        v-if="checkUrl() != '/login' && !is_login"
                    >
                        <a class="nav-link" href="/login">Login</a>
                    </li>

                    <li class="nav-item" v-if="is_login">
                        <a
                            class="nav-link"
                            href="javascript:void(0);"
                            @click="logout"
                            v-if="logout_url"
                            >Logout</a
                        >
                        <a
                            class="nav-link"
                            v-if="logout_url == undefined"
                            href="/logout"
                            >Logout</a
                        >
                    </li>
                </ul>

                <div 
                    class="add_article_icon"
                    v-if="is_login && type!='admin'"
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
                
                <div v-if="is_login" class="notifications_wrapper">
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
                            <a class="dropdown-item" href="/read_all_notifications">
                                <div
                                    style="background:red;color:#fff;"
                                    class="badge badge-light"
                                >
                                    {{ notification_num }}
                                </div>
                                All Message</a
                            >
                            <a v-if="unread_notifications" class="dropdown-item" href="/read_notifications">
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

        <register-component :is_show="is_show"></register-component>
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
            request_url="/articles_add"
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
export default {
    props: [
        "is_login",
        "notify_data",
        "request_url",
        "gray_color",
        "logout_url",
        "type",
        'username'
    ],
    data() {
        return {
            is_show: false,
            notification_num: 0,
            unread_notifications: 0,
            current_url: "",
            show_article_component: false
        };
    },

    mounted() {
        if (this.notify_data != undefined) {
            this.notification_num = this.notify_data.length;
            for (let i = 0; i < this.notify_data.length; i++) {
                if (!this.notify_data[i].read_at) {
                    this.unread_notifications += 1;
                }
            }
        }
    },

    methods: {
        openRegForm() {
        },

        add_articles() {
            this.$refs.article_component.showDialog();
        },

        logout() {
            if (localStorage.getItem("jwt_token")) {
                axios.post(this.logout_url, {}).then(res => {
                    window.location.reload();
                });
            } else {
                alert("Error Request!");
            }

            window.location.reload();
        },

        checkUrl() {
            if (this.request_url != "") {
                return new URL(this.request_url).pathname;
            }
        }
    }
};
</script>


