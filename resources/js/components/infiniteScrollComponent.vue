<template>
  <div class="infinite-list-wrapper">
    <el-table :data="tableData" v-el-table-infinite-scroll="loadMoreData">
    </el-table>
    <p v-if="loading">加载中...</p>
    <p v-if="noMore">没有更多了</p>
  </div>
</template>
<style >
   ul{
       list-style: none;
       margin:0px;
       padding:0px;
   }
    .list-item{
        text-align: center;
        color:#fff;
        height:30px;
        width:100%;
        border:2px solid #fff;
        background: rgb(148, 155, 123);
    }
</style>
<script>
  export default {
    data () {
      return {
        count: 10,
        loading: false
      }
    },
    computed: {
      noMore () {
        return this.count >= 20
      },
      disabled () {
        return this.loading || this.noMore
      }
    },
    methods: {
      load () {
        this.loading = true

      }
    },
    mounted(){
        let url = "/articles";
        let submit_data = {};
        axios.get(url, submit_data).then(res => {
            console.log(data);
            // let { status, data } = res;
            // if (status == "200") {
            //     //_this.value = false;
            //     if(data.code === "0"){
            //         setTimeout(function(){
            //             window.location.reload();
            //         }, 700);
            //     }
            // }
        });
    }
  }
</script>