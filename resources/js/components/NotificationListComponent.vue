<template>
    <div class="row">
         <div class="row">
            <message-component
                v-if="success_response"
                :status="status"
                :message="msg"
            ></message-component>
        </div>
            <div v-if="message" class="row notification_row">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Create Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                     <tbody v-if="message">
                        <tr  v-for="(item,index) in message" :key="index+1">
                            <td >
                                {{item.data.name}} 点赞了你的文章 《{{item.data.article_title}}》
                            </td>
                            <td>
                                {{item.created}}
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <div>
                                        <button type="button" 
                                            :class="item.read_at==undefined ? 'btn-primary':'read_btn'"  
                                            @click="handleMarkAsRead(item.id,index)" 
                                            id="mark_as_read_btn" 
                                            class="btn"
                                            :disabled=item.read_at
                                            ref="remark_btn"
                                        >
                                        {{item.read_at==undefined ? btn_message:read_btn_message}}
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody> 
                </table>
            </div>
    </div>
</template>
<style scoped>
    .read_btn{
        background:#ccc;
        color:#fff;
    }
</style>
<script>
    export default {
        props:[
            'message',
            "request_url",
            "status",
            "msg",
            "redirect_url"
        ],

        methods:{
            async handleMarkAsRead(message_id,index){
                if(message_id){
                    let submit_data = {
                        "nid":message_id
                    }
                    let config = {};
                    let url = this.request_url;
                    let res = await axios.post(url, submit_data,config);
                    let _this = this;
                    let {status,data} = res;
                    //console.log(data);
                    if(status == "200" && data.code!="-1"){ 
                        this.success_response = true;
                        if(this.redirect_url!=undefined && this.redirect_url=='reload'){
                            window.location.reload();
                        }else{
                            if(index!=undefined){ 
                                this.$refs.remark_btn[index].className = "btn read_btn";
                                this.$refs.remark_btn[index].innerHTML = "已标记";
                            }
                        }
                    }
                }
            }
        },

        data() {
            return {
                success_response:false,
                btn_message:'标记为已读',
                read_btn_message:'已标记',
            }
        },
        mounted() {

           if(this.message==""){
               return false;
           }
        }
    }
</script>
