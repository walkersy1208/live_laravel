<template>
<div>
    <spaheader-component 
            ref="headerCom"
            :is_login="auth"
            v-if="auth && message"
           
        >
        </spaheader-component> 
   <div class="container">
            <notificationlist-component
                :message = "message"
                request_url = "/spa/mark_read"
                redirect_url=""
                status='success'
                msg="标记成功"
            >
            </notificationlist-component>
         
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
        <!--<div>
           
         <spaheader-component 
            ref="headerCom"
            :is_login="auth"
            v-if="auth && message"
        >   
        </spaheader-component> -->
        
      
        <!-- <div class="container">
            <notificationlist-component
                :message = "message"
                request_url = "/spa/mark_read"
                redirect_url=""
                status='success'
                msg="标记成功"
            >
            </notificationlist-component>

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
    </div>  -->
</template>
<style>
   
</style>
<script>
//import MessageComponent from "../MessageComponent.vue";
import {mapState} from 'vuex';   

export default {
    //components: { MessageComponent },
    props: [
        'action'
    ],

    computed:{
        ...mapState({
            auth: (state) => state.AuthUser.auth,
            auth_id: (state) => state.AuthUser.id,
        }),
    },

    created(){
        if(localStorage.getItem('passport_token')){
            this.$store.dispatch("getUserInfo");
        }
    },

    data() {
        return {
            message:"",
            links:"",
            success_response:false,
            username:"",
            password:"",
            status:'success',
            msg:"登陆成功",
            total_nums:0,
            per_page:0,
            cur_page:0,
        };
    },
    
    methods: {
        setData(data){
            this.message = data.items;
            this.per_page = parseInt(data.page.per_page);
            this.cur_page = parseInt(data.page.current_page);
            this.total_nums = parseInt(data.page.total);
        },

        async page_change(page){
            this.cur_page = page;
            let submit_data = {
               "page":this.cur_page
            }
            let config = {};
            let url = "";
            if(this.action == "all_message"){ 
                url = "/spa/read_all_notifications";
            }else{
                url = "/spa/read_notifications";
            }
            let res = await axios.post(url, submit_data,config);
            let _this = this;
            let {status,data} = res;
            if(status == "200"){ 
               // _this.nextTick(function(){
                    this.setData(data);
               // });
            }
        }
       
    },

    async create(){
        
    },

    async mounted() {
        let url = "";
        if(this.action == "all_message"){ 
            url = "/spa/read_all_notifications";
        }else{
            url = "/spa/read_notifications";
        }

        let submit_data = {};
        let config = {};
        let res = await axios.post(url, submit_data,config);
        let {status,data} = res;
        let _this = this;
        if(data.code == "0"){
           this.setData(data);
        }
    }
};
</script>
