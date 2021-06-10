<template>
    <div class="container">
        <message-component
            v-if="success_response"
            :status="status"
            :message="msg"
        ></message-component>
       
        <div @click="openDialog" class="add_role_btn">
            <slot name="add_role_btn" ></slot>
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
    props: ["title", "is_input", "fields", "msg", "status", "request_url","redirect_url","need_token_url","trigger","component_data"],
    data() {
        return {
            dialogVisible: false,
            default_title: "提示",
            input_info: {},
            success_response: false,
            
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

        handleComfirm() {
            if (this.fields) {
                let submit_data = {};
                for (let i in this.fields) {
                    if (this.input_info[this.fields[i].name] != undefined) {
                        submit_data[this.fields[i].name] = this.input_info[
                            this.fields[i].name
                        ];
                    }
                }

                let _this = this;
                let url = this.request_url;
                // console.log(submit_data);
                axios.post(url, submit_data).then(res => {
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
                        if(data.token!= undefined){
                            localStorage.setItem('jwt_token', data.token)
                        }
                        
                        let _this = this;
                        //let token = localStorage.getItem('jwt_token');
                        setTimeout(function(){
                            if(_this.redirect_url){
                                if(localStorage.getItem('jwt_token') && _this.need_token_url){
                                    window.location.href = _this.redirect_url+"?token="+localStorage.getItem('jwt_token');
                                    //window.location.href = _this.redirect_url;
                                }else{ 
                                    window.location.href = _this.redirect_url;
                                }
                            }else{
                                window.location.reload();
                            }
                         
                        }, 1000);
                        
                    }
                });
            }
           
        },

        handleClose(done) {
            this.dialogVisible = false;
        },

        handle_input(args) {
            
        }
    },

    mounted() {
        
        if(this.component_data){
            this.formateData(this.component_data);
            this.fields = this.component_data;
        }


       
        if (this.fields) {
            this.input_info = this.fields;
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
