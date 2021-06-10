<template>
     
</template>

<script>
  export default {
    props:[
       "status",
       "message",
    ],
    data(){
       return {
         msg_type:"",
         msg:"",
       }
    },
    mounted(){
      this.msg_type = this.status;
      this.msg = this.message;
      this.openMsg();
    },
    methods: {
      openMsg() {
        this.$message({
          showClose: true,
          message: this.msg,
          type: this.msg_type
        });
      }
    }
  }
</script>