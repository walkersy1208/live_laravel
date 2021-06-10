<template>
    <div class="row">
        <div class="row">
            <button type="button" class="btn  btn-primary edit-btn" 
                :class="[ btn_style_type  ? btn_style_type.class: '' ]"
                v-if="type=='edit'">
                <div @click="showDialog()" >
                    <i :class="[ btn_style_type  ? btn_style_type.icon: 'el-icon-edit' ]"></i>
                </div>
            </button>
        </div>
        <div class="row">
            <message-component
                v-if="success_response"
                :status="status"
                :message="msg"
            ></message-component>
        </div>
       
        <el-dialog 
            :title="default_title"
            :visible.sync="dialogVisible"
            :before-close="handleClose"
            v-if="dialogVisible"
        >
            <span>
                <div >
                    <div
                        v-for="(item, key) in fields"
                        :key="key"
                        style="margin-bottom:20px;"
                    >
                        
                        <div class="row" v-if="item.type=='text'|| item.type=='password'">
                            <div style="display:none;">{{ input_info[item.name] = item.val }}</div>
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
                          
                        <div class="row"  v-if="item.type=='search'">
                           
                           <search-component 
                                @changInput="search_value"
                                :data_list="item.val"
                            >
                            </search-component>
                        </div>

                        <div class="row" v-if="item.type=='hidden'">
                            <input type="hidden" :value="item.val">
                        </div>

                        <div class="row" v-if="item.type=='image_url'">
                            <upload-component :upload_item_name="item.name" @change_image="change_preview_image" :fileList_data="[]"></upload-component>
                        </div>

                        <div class="row" v-if="item.type=='mobile_image_url'">
                            <upload-component :upload_item_name="item.name" @change_image="change_preview_image" :fileList_data="[]"></upload-component>
                        </div>

                        <div class="row" v-if="item.type=='editor'">
                            <editor-component @update="getContent" :name="item.name" :default_value="item.val"></editor-component>
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
<style>
    .el-dialog__footer {
         padding: 50px 20px 20px;
    }
</style>
<script>
import MessageComponent from "./MessageComponent.vue";
import SearchComponent from './searchComponent.vue';


export default {
    components: { MessageComponent, SearchComponent },
    props: ["title", "is_input", "fields", "msg", "status", "request_url","redirect_url","need_token_url","trigger","component_data","type","btn_style_type"],
    data() {
        return {
            dialogVisible: false,
            default_title: "提示",
            input_info: {
               
            },
            success_response: false,
            image_file_change:"",
            mobile_image_file_change:"",
            radio_default_show:"Y",
            upload_item_name:"",
            editor_content:"",
            tags:"",
        };
    },
    methods: {
        getContent(content){
            this.input_info["content"] = content;
        },

        openDialog() {
            var all_el = document.querySelectorAll(".role_error_field_msg");
            all_el.forEach(function(v, i) {
                v.innerHTML = "";
            });
            this.dialogVisible = true;
        },

        showDialog(){
            this.dialogVisible = true;
        },

        search_value(val){
            if(val!=undefined){
                this.input_info["tags"] = val;
            }
        },

        async handleComfirm() {
            
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
                //console.log(submit_data);
                
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
                        this.$emit("update_record");
                        if(data.update_record){
                            let update_record = data.update_record;
                            if(this.redirect_url!=""){ 
                                let articles = this.$parent.$parent.articles;
                                this.$parent.$parent.articles = articles.map((item)=>{
                                    if(item.id == update_record.id){
                                        item.title   = update_record.title;
                                        item.content  = update_record.content;
                                    }
                                    return item;
                                });
                            }
                            

                            //let articles = this.$parent.$parent.articles;

                            //console.log(update_record);
                            // console.log(articles.map((item)=>{
                            //     console.log(item.user.name);
                            //     // if(item.id == update_record.id){
                            //     //     item  = update_record;
                            //     // }
                            //     // return item;
                            // }));
                        }

                        //success
                        this.dialogVisible = false;
                        this.success_response = true;
                        //check if has token
                        if(data.token!= undefined){
                            localStorage.setItem('jwt_token', data.token)
                        }
                        
                        if(this.request_url.indexOf("spa")=="-1" ){ 
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
        
        if(this.component_data){
            this.formateData(this.component_data);
            this.fields = this.component_data;
        }

        if(this.trigger){
            this.dialogVisible = true;
        }

        if (this.title != undefined) {
            this.default_title = this.title;
        }
    }
};
</script>
