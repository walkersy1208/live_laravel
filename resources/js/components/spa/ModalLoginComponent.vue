<template>
    <div class="row">
        <message-component
            v-if="success_response"
            :status="status"
            :message="msg"
        ></message-component>
       
        <div @click="openDialog" class="add_role_btn">
            <slot name="add_role_btn"></slot>
        </div>

        <el-dialog
            :title="default_title"
            :visible.sync="dialogVisible"
            :before-close="handleClose"
        >
            <span>
                <div v-if="is_input && fields">
                    <div
                        v-for="(item, key) in fields"
                        :key="key"
                        style="margin-bottom:20px;"
                    >
                        <div class="row" v-if="item.type=='text'|| item.type=='password'">
                            <input
                                :type="item.type"
                                class="form-control"
                                v-model="input_info[item.name]"
                                :name="item.name"
                                :placeholder="item.ph"
                            />
                            <div
                                :class="[
                                    'role_error_field_msg',
                                    'error_' + item.name
                                ]"
                                :ref="item.name"
                            ></div>
                        </div>

                        <div class="row" v-if="item.type=='radio'">
                                <div v-if="item.type=='radio'"> 
                                    <el-radio v-model="input_info[item.name]" label="Y">是</el-radio>
                                    <el-radio v-model="input_info[item.name]" label="N">否</el-radio>
                                </div>
                            <div
                                :class="[
                                    'role_error_field_msg',
                                    'error_' + item.name
                                ]"
                                :ref="item.name"
                            ></div>
                        </div>

                        <div class="row" v-if="item.type=='image_url'">
                            <upload-component :upload_item_name="item.name" @change_image="change_preview_image" :fileList_data="[]"></upload-component>
                        </div>

                        <div class="row" v-if="item.type=='mobile_image_url'">
                            <upload-component :upload_item_name="item.name" @change_image="change_preview_image" :fileList_data="[]"></upload-component>
                        </div>
                    </div>
                </div>
                <slot name="msg_content"> </slot>
            </span>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="handleComfirm"
                    >确 定</el-button
                >
            </span>
        </el-dialog>
    </div>
</template>

<script>
import MessageComponent from "../MessageComponent.vue";
export default {
    components: { MessageComponent },
    props: ["title", "is_input", "fields", "msg", "status", "request_url","redirect_url","need_token_url","trigger","component_data"],
    data() {
        return {
            dialogVisible: false,
            default_title: "提示",
            input_info: {
                "is_visible":'Y',
            },
            success_response: false,
            image_file_change:"",
            mobile_image_file_change:"",
            radio_default_show:"Y",
            upload_item_name:"",
        };
    },
    methods: {
        openDialog() {
           var all_el = document.querySelectorAll(".role_error_field_msg");
            all_el.forEach(function(v, i) {
                v.innerHTML = "";
            });
            this.dialogVisible = true;
        },

        async handleComfirm() {
            //init token
            // if(localStorage.getItem('passport_token')){
            //     localStorage.removeItem('passport_token');
            // }

            if (this.fields) {
                
                let submit_data = {};
               
                for(let i in this.input_info){
                   submit_data[i] = this.input_info[i];
                }

                let config = {};
                var form = new FormData();
                //单个文件上传的操作
                if(this.image_file_change){
                    let base64_image =  await this.readerImage(this.image_file_change.raw);
                    form.append("file",base64_image);
                }
                
                if(this.mobile_image_file_change){
                    let mobile_base64_image =  await this.readerImage(this.mobile_image_file_change.raw);
                    form.append("mobile_file",mobile_base64_image);
                }

                for(let i in submit_data){ 
                    form.append(i, submit_data[i]);
                }

                config = {
                    header : {
                        'Content-Type' : 'multipart/form-data'
                    }
                }

                submit_data = form;
                let _this = this;
                let url = this.request_url;
                
                //Need Vuex action check login

                axios.post(url, submit_data,config).then(res => {
                    let { status, data } = res;
                    var all_el = document.querySelectorAll(
                        ".role_error_field_msg"
                    );

                    all_el.forEach(function(v, i) {
                        v.innerHTML = "";
                    });

                    if (data.code == "-1") {
                        let errors = data.errors;
                        for (let i in errors) {
                            var el = document.querySelector(".error_" + i);
                            el.innerHTML = errors[i];
                        }
                    } else {
                        //success
                        this.dialogVisible = false;
                        this.success_response = true;
                        //check if has token
                        //console.log(data);
                        if(data.data == undefined){

                            if(data.token!= undefined){
                                localStorage.setItem('jwt_token', data.token);
                            }
                            
                        }else{ 
                        
                            //save passport token
                            if(data.data.access_token!=undefined){
                                //console.log(data.data.access_token);
                                //init axios header

                                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.data.access_token;
                                localStorage.setItem('passport_token', data.data.access_token);
                            }

                        }

                        //console.log(localStorage.getItem('passport_token'));
                        if(localStorage.getItem('passport_token')){
                            this.$store.dispatch("getUserInfo");
                        }
                       

                        return false;

                        let _this = this;
                       
                        setTimeout(function(){
                            if(_this.redirect_url){

                                if(localStorage.getItem('jwt_token') && _this.need_token_url){
                                    if(_this.redirect_url.indexOf("token")!= -1){ 
                                        window.location.href = _this.redirect_url;
                                    }else{
                                        window.location.href = _this.redirect_url+"?token="+localStorage.getItem('jwt_token');
                                    }
                                }else{ 
                                    window.location.href = _this.redirect_url;
                                }

                            }else{
                                window.location.reload();
                            }
                         
                        }, 700);
                        
                    }
                });
            }
           
        },

        change_preview_image(file,upload_item_name){
            if(upload_item_name == "image_url"){
                this.upload_item_name = upload_item_name;
                this.image_file_change = file;
            }else{
                this.upload_item_name = upload_item_name;
                this.mobile_image_file_change = file;
            }
            
        },

        handleClose(done) {
            this.dialogVisible = false;
        },

        handle_input() {
            
        },

        readerImage(file){
            return new Promise((resole,reject)=>{
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload =  function (e){
                    resole(e.target.result);
                };
            });
        },
    },

    mounted() {
        
        // let request_url = this.request_url;
        // if(localStorage.getItem("jwt_token")){
        //     window.location.href= '/api/articles_review?token='+localStorage.getItem("jwt_token");
        // }

        if(this.component_data){
            this.formateData(this.component_data);
            this.fields = this.component_data;
        }

       // console.log(this.fields);


       
        if (this.fields) { 
        }

        //console.log(this.input_info);
        //return false;
        if(this.trigger){
            this.dialogVisible = true;
        }

        if (this.title != undefined) {
            this.default_title = this.title;
        }
    }
};
</script>
