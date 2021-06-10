<template>
    <div class="container">
            <message-component
                v-if="response_message"
                :status="status"
                :message="message"
            ></message-component>

            <el-select
                v-model="value"
                clearable
                placeholder="请选择"
                @change="changeStatus($event)"
            >
                <el-option
                    @change="changeStatus(item.value)"
                    v-for="item in options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                >
                </el-option>
            </el-select>
        
    </div>
</template>

<script>
export default {
    props: ["data_status", "all_select_data","aid"],
    data() {
        return {
            response_message:false,
            message:"",
            status:"",
            options: [],
            value: ""
        };
    },

    methods: {
        changeStatus(val) {
            if(!val){
                return false;
            }
            let status = val;
            let data = {
                aid: this.aid,
                status: status
            };
            
            axios.post("/api/articles_status_change", data).then(res => {
                let { status, data } = res;
                if (status == "200") {
                    
                    if(data.code==="-1"){
                        this.status = "danger";
                        this.message = "更新失败";
                    }else{
                        this.status = "success";
                        this.message = "更新成功";
                    }
                    this.response_message = true;
                    
                    setTimeout(function(){
                        window.location.reload();
                    },500);
                } else {

                }
            });
        }
    },
    
    mounted() {

        //console.log(this.message);
       
        let _this = this;
        this.options = this.all_select_data.filter(function(i,v){
            return i.value!=_this.data_status;
        });

        
        //this.value = this.data_status;
        if (this.data_status == "-1") {
            this.value = "DisApproval";
        } else if (this.data_status == "1") {
            this.value = "Approval";
        } else {
            this.value = "Pending";
        }
        /*this.all_select_data.forEach(function(i,v){
            console.log(i);
            //.log(v);
        });*/
    }
};
</script>
