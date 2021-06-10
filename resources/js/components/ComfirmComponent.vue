<template>
      <div class="row">
          <button type="button" class="btn btn-danger del_btn">
              <div @click="open()" style="color:#fff;" ><i class="el-icon-delete"></i></div>
          </button>
      </div>
</template>
<style scoped>
 
</style>
<script>
  export default {
    props:[
      "url",
      "msg",
      "request_url",
      "data",
      "small_size",
      "del_id",
      "redirect_url",
    ],

    data(){
      return {
       
      }
    },
    mounted(){
      this.go_url = this.url;
      
    },
    methods: {
      open() {
        this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'

        }).then(() => {
            let _this = this;
           
            axios.post(_this.request_url, {}).then(res =>{
                let {status,data} = res;
                
                if(status == "200"){
                    
                  this.$message({
                      type: 'success',
                      message: '删除成功!'
                  });

                  
                  
                  if(this.del_id == undefined){ 
                      setTimeout(function(){ 
                          window.location.reload();
                      }, 1000);

                  }else{

                      let articles = this.$parent.$parent.articles;
                            this.$parent.$parent.articles = articles.filter((item)=>{
                                return item.id != _this.del_id;
                      });
                      this.$router.go(0);
                  }

                }else{

                }
            });
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消删除'
          });          
        });
      }
    }
  }
</script>