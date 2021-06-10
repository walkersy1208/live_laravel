<template>
    <div class="row">
        <el-menu
            class="el-menu-vertical-demo"
            :default-active="current_index"
            @open="handleOpen"
            @close="handleClose"
            :collapse="isCollapse"
        >
            <el-submenu
                :index="(key + 1).toString()"
                v-for="(item, key) in menu_items"
                :key="key"
               
            >
                <template slot="title">
                    <i :class="item.icon"></i>
                    <span slot="title">{{ item.name }}</span>
                </template>

                <el-menu-item-group
                    if="item.subItem"
                    v-for="(subitem, subkey) in item.subItem"
                    :key="subkey"
                >
                    <!-- <el-menu-item   :index="subitem.index" @click="direct_url(subitem.url)" -->
                    <el-menu-item  :index="subitem.index" @click="direct_url(subitem.url)"
                        >{{ subitem.name }}
                    </el-menu-item>
                </el-menu-item-group>
            </el-submenu>
        </el-menu>
    </div>
</template>
<style>
.el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 400px;
}

.el-menu-item-group__title {
    display: none;
}

.el-menu--popup {
    padding: 0px;
}
</style>
<script>
export default {
    props:[
        'current_url'
    ],
    data() {
        return {
            isCollapse: false,
            offsetWidth: "",
            jwt_token:"",
            current_index:'1',
            parseUri:[
                {"uri":"api/articles_review","index":"2-1"},
                {"uri":"api/showAdmin","index":"1-1"},
                {"uri":"api/carousel","index":"3-1"}
            ],
            menu_items: [
                {
                    name: "管理员管理",
                    subItem: [
                        {
                            name: "管理员列表",
                            url: "/api/showAdmin",
                            index: "1-1"
                        },

                        /*
                        { 
                            name: "item2", 
                            url: "/admin/showAdmin2",
                            index: "1-2" 
                        },
                        { 
                            name: "item3",
                            url: "/admin/showAdmin3", 
                            index: "1-3" 
                        }*/
                    ],
                    icon: "el-icon-user-solid"
                },

                {
                    name: "文章管理",
                    subItem: [
                    {
                        name: "审核管理",
                        url: "/api/articles_review",
                        index: "2-1"
                    },
                    ],
                    icon: "el-icon-menu"
                },

                {
                    name: "Carousel管理",
                     subItem: [
                    {
                        name: "carousel列表",
                        url: "/api/carousel",
                        index: "3-1"
                    },
                    ],
                    icon: "el-icon-picture"
                }
            ]
        };
    },
    methods: {
        handleOpen(key, keyPath) {
            console.log(key, keyPath);
        },

        // handleSubItemOpen(key,keyPath){
        //     alert("2");
        //     console.log(key, keyPath);
        //     return false;
        // },

        direct_url(url){
            if(url!=undefined && this.jwt_token){
                window.location.href=url+"?token="+this.jwt_token;
            }
        },

        handleClose(key, keyPath) {
            console.log(key, keyPath);
        }
    },
    mounted() {
        //parse current uri 
        if(this.parseUri != undefined){ 
            for(let i in this.parseUri){
                if(this.parseUri[i].uri == this.current_url){
                    //console.log(this.current_index);
                    this.current_index = this.parseUri[i].index;
                }
            }
        }

        //检查是否存在token
        if(localStorage.getItem("jwt_token")){
            this.jwt_token = localStorage.getItem("jwt_token");
        }

        //return false;
        this.offsetWidth = document.body.offsetWidth;
        if (this.offsetWidth < 768) {
            this.isCollapse = true;
        }
        window.onresize = () => {
            return (() => {
                this.offsetWidth = document.body.offsetWidth;
                if (this.offsetWidth < 768) {
                    this.isCollapse = true;
                } else {
                    this.isCollapse = false;
                }
            })();
        };
    }
};
</script>
