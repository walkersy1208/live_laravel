<template>
    <div class="container">
        <div class="regForm_wrapper" v-show="is_show">
            <el-form
                :model="ruleForm"
                status-icon
                :rules="rules"
                ref="ruleForm"
                label-width="100px"
                class="demo-ruleForm"
            >
                <el-form-item label="邮件" prop="email">
                    <el-input v-model="ruleForm.email"></el-input>
                </el-form-item>

                <el-form-item label="密码" prop="password">
                    <el-input
                        type="password"
                        v-model="ruleForm.password"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>

                <el-form-item label="确认密码" prop="password_confirmation">
                    <el-input
                        type="password"
                        v-model="ruleForm.password_confirmation"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="submitForm('ruleForm')"
                        >提交</el-button
                    >
                    <el-button @click="resetForm('ruleForm')">重置</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>
<script>
export default {
    props: ["is_show"],

    data() {
        var checkEmail = (rule, value, callback) => {
            const mailReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
            if (!value) {
                return callback(new Error("邮箱不能为空"));
            }

            setTimeout(() => {
                if (mailReg.test(value)) {
                    //验证email唯一性
                    this.uniqueEmail(value, callback);
                    //console.log(data);

                    
                   

                } else {

                    callback(new Error("请输入正确的邮箱格式"));
                }
            }, 100);
        };

        var validatePass = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("请输入密码"));
            } else {
                if (this.ruleForm.password_confirmation !== "") {
                    this.$refs.ruleForm.validateField("password_confirmation");
                }
                callback();
            }
        };

        var validatePass2 = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("请再次输入密码"));
            } else if (value !== this.ruleForm.password) {
                callback(new Error("两次输入密码不一致!"));
            } else {
                callback();
            }
        };

        return {
            centerDialogVisible: false,
            ruleForm: {
                password: "",
                password_confirmation: "",
                email: ""
                
            },
            rules: {
                password: [{ validator: validatePass, trigger: "blur" }],
                password_confirmation: [
                    { validator: validatePass2, trigger: "blur" }
                ],
                //age: [{ validator: checkAge, trigger: "blur" }]
                email: [
                    { validator: checkEmail, trigger: "blur" }
                    //{ pattern:/[A-Za-z]+/, message: '请输入正确的名字' },
                ]
                //name: [
                //  { validator: checkName, trigger: "blur" },
                //{ pattern:/[A-Za-z]+/, message: '请输入正确的名字' },
                //]
            }
        };
    },
    methods: {
        async uniqueEmail(value,fun){
            if(value!==undefined){
                let url = "/spa/checkEmailUnique";
                let submit_data = {
                    "email":value,
                };
                let config = {};
                let {code,data} = await axios.post(url, submit_data,config);
                
                if(data.code == "-1"){
                   // console.log(data.data);
                fun(new Error(data.errors.email[0]));
                }
                //return true
               
            }
        },

        submitForm(formName) {
            let _this = this;
            this.$refs[formName].validate(valid => {
              
                if (valid) {
                    let email = this.ruleForm.email;
                    let password = this.ruleForm.password;
                    let password_confirmation = this.ruleForm
                        .password_confirmation;
                    
                    let data = {
                        email,
                        password,
                        password_confirmation
                    };

                    axios.post("/register_form", data).then(res => {
                        let { status, data } = res;

                        let formFieldsName = {
                            "0": "email",
                            "1": "password"
                        };

                        if (data.code == "-1") {
                            for (let i in data.errors) {
                                if (i == "email") {
                                    //console.log(_this.$refs[formName]);
                                    _this.$refs[
                                        formName
                                    ].fields[0].validateMessage = data.errors[
                                        i
                                    ].join("");
                                    _this.$refs[
                                        formName
                                    ].fields[0].validateState = "error";
                                } else if (i == "password") {
                                    _this.$refs[
                                        formName
                                    ].fields[1].validateMessage = data.errors[
                                        i
                                    ].join("");
                                    _this.$refs[
                                        formName
                                    ].fields[1].validateState = "error";
                                }
                            }
                        }
                    });
                } else {
                    console.log("error submit!!");
                    return false;
                }
            });
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        }
    },
    mounted() {
        //console.log("Component register.");
    }
};
</script>

<style>
.el-form-item__label {
    text-align: left;
}

@media (min-width: 768px) {
    .regForm_wrapper {
        max-width: 60%;
        margin: 0 auto;
    }
}
</style>
