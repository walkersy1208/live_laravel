<template>
    <div class="container">
        
        <message-component
            v-if="success_response"
            :status="status"
            :message="msg"
        ></message-component>
       
        <el-dialog
            v-if='dialogVisible'
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
                        
                        <input
                            v-if="item.type == 'text' || item.type == 'password' "
                            :type="item.type"
                            class="form-control"
                            v-model="input_info[item.name] = item.val"
                            :name="item.name"
                            :placeholder="item.val != '' ? item.val : item.ph"
                           
                        />

                        <div v-if="item.type=='radio'">
                            <el-radio v-model="item.val" label="Y">是</el-radio>
                            <el-radio v-model="item.val" label="N">否</el-radio>
                        </div>

                        <div v-if="item.type=='upload_image'" >
                            <upload-component @change_image="change_preview_image" :fileList_data="[{
                                name:item.name,
                                url:item.val,
                            }]"  :upload_id="id=item.id" :upload_item_name="item.name"></upload-component>
                        </div>

                         <div v-if="item.type=='mobile_upload_image'" >
                            <upload-component @change_image="change_preview_image" :fileList_data="[{
                                name:item.name,
                                url:item.val,
                            }]" :upload_item_name="item.name"></upload-component>
                        </div>

                        <div
                            :class="[
                                'role_error_field_msg',
                                'error_' + item.name
                            ]"
                            :ref="item.name"
                        ></div>
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
import MessageComponent from "./MessageComponent.vue";
export default {
    components: { MessageComponent },
    props: ["title", "is_input", "fields", "msg", "status", "request_url","edit_btn"],
    data() {
        return {
            dialogVisible: false,
            default_title: "提示",
            input_info: {},
            success_response: false,
            fileListData:"",
            image_file_change:"",
            mobile_image_file_change:"",
            base64_image_data:"",
            upload_item_name:'',
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
            if (this.fields) {
                
                let submit_data = {};
                for (let i in this.fields) {
                   submit_data[this.fields[i]["name"]] = this.fields[i]["val"];
                   if(submit_data["id"] == undefined && this.fields[i]["id"]){
                       submit_data["id"]= this.fields[i]["id"];
                   }
                }

                let config ={};
                var form = new FormData();
                if(this.image_file_change){
                    //submit_data["old_image_url"] = submit_data["image_url"];
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
               
                let url = this.request_url;
                axios.post(url, submit_data,config).then(res => {
                    let { status, data } = res;
                    //init dom innerHTML
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
                        
                        setTimeout(function(){ 
                          window.location.reload();
                        }, 700);
                        
                    }
                });
            }
           
        },

        handleClose(done) {
            this.dialogVisible = false;
        },

        async getBase64File(file){
           let data = await this.readerImage(file);
           return Promise.resolve(data);
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

        
        change_preview_image(file,upload_item_name){
            if(upload_item_name == "image_url"){
                this.upload_item_name = upload_item_name;
                this.image_file_change = file;
            }else{
                this.upload_item_name = upload_item_name;
                this.mobile_image_file_change = file;
            }
            //this.image_file_change = file;
        },

        handle_input(args) {
              
        }
    },

    mounted() {
        //console.log(this.fields);
        //return false;
        if (this.fields) {
            this.input_info = this.fields;
        }

        if (this.title != undefined) {
            this.default_title = this.title;
        }
    }
};
</script>
