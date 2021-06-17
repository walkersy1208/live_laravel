<template>
    <div class="row">
        <message-component
            v-if="success_response"
            :status="status"
            :message="msg"
        ></message-component>

        <div class="login_form_warapper" >
           
                <div class="form-group">
                    <input
                        type="email"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                        placeholder="Enter email"
                        v-model="username"
                    />
                    <small id="emailHelp" class="form-text text-muted"
                        ></small
                    >
                </div>
                <div class="form-group">
                    <input 
                        v-model="password"
                        type="password"
                        class="form-control"
                        id="exampleInputPassword1"
                        placeholder="Password"
                    />
                </div>
                <button  @click="handleComfirm" class="btn btn-primary">确定</button>
        </div>
    </div>
</template>
<style>
   .login_form_warapper {
        width:80%;
        position: absolute;
        left: 50%;
        top: 50%;
        border-radius: 10px;
        background: #3333330a;
        padding: 30px;
        transform: translate(-50%, -50%);
        background-image: url('/images/1120.png');
        max-width:760px;
    }

    @media(max-width:767px){
        .login_form_warapper {
            max-width:80%;
        }
    }
</style>
<script>
import MessageComponent from "../MessageComponent.vue";
export default {
    components: { MessageComponent },
    props: [
      
    ],
    data() {
        return {
            success_response:false,
            username:"",
            password:"",
            status:'success',
            msg:"登陆成功"
        };
    },
    methods: {
       
        async handleComfirm() {
            
            if (this.username && this.password) {
                let submit_data = {
                    username:this.username,
                    password:this.password
                };
                let _this = this;
                let url = "/spa/login";
                let config = {};

                axios.post(url, submit_data, config).then(res => {
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
                        
                        if (data.data == undefined) {
                            if (data.token != undefined) {
                                localStorage.setItem("jwt_token", data.token);
                            }
                        } else {
                            //save passport token
                            if (data.data.access_token != undefined) {
                                window.axios.defaults.headers.common[
                                    "Authorization"
                                ] = "Bearer " + data.data.access_token;

                                localStorage.setItem(
                                    "passport_token",
                                    data.data.access_token
                                );
                                
                                localStorage.setItem(
                                    "token_expire_date",
                                    new Date().getTime() + 60*60*24,
                                );
                            }
                        }

                        if (localStorage.getItem("passport_token")) {
                            this.$store.dispatch("getUserInfo");
                        }

                        this.$router.push({ name: 'home'});
                        return false;
                    }
                });
            }
        },
    },

    mounted() {
        
      

    }
};
</script>
