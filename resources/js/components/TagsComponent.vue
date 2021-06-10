<template>
    <div class="container tags_container">
        <el-tag
            :key="tag"
            v-for="tag in dynamicTags"
            closable
            :disable-transitions="false"
            @close="handleClose(tag)"
        >
            {{ tag }}
        </el-tag>
        <el-input
            class="input-new-tag"
            v-if="inputVisible"
            v-model="inputValue"
            ref="saveTagInput"
            size="small"
            @keyup.enter.native="handleInputConfirm"
            @blur="handleInputConfirm"
        >
        </el-input>
        <el-button v-else class="button-new-tag" size="small" @click="showInput"
            >+ New Tag</el-button
        >

        <input type="text" v-model="tags_value" name="tags">
    </div>
</template>

<style>
  .el-tag + .el-tag {
    margin-left: 10px;
   
  }
  .button-new-tag {
    margin-left: 10px;
    height: 32px;
    line-height: 30px;
    padding-top: 0;
    padding-bottom: 0;
  }
  .input-new-tag {
    width: 90px;
    margin-left: 10px;
    vertical-align: bottom;
  }

  .tags_container{
    margin-bottom: 20px;
  }
</style>

<script>
  export default {
    props:[
      "aid"
    ],
    data() {
      return {
        dynamicTags: [],
        dynamicTags_id:[],
        inputVisible: false,
        inputValue: '',
        tags_value:'',
        article_id:"",
      };
    },

    mounted(){
        //this.tags_value = this.dynamicTags;
        this.tags_value = this.dynamicTags_id;
        
        this.article_id = this.aid;
     
        //获取文章相关的tag
        let data = {
           article_id:this.article_id,
        }
        axios.post("/article_tags", data).then(res => {
              let { status, data } = res;
              for(let i  in data){
                let id = data[i].id;
                let name = data[i].name;
                this.dynamicTags_id.push(id);
                this.dynamicTags.push(name);
              }
              return ;
              if (status == "200") {
                 
              } else {

              }
        });
    },
    methods: {
      handleClose(tag) {
        console.log(tag);
        this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
        this.dynamicTags_id.splice(this.dynamicTags_id.indexOf(tag), 1);
      },

      showInput() {
        this.inputVisible = true;
        this.$nextTick(_ => {
          this.$refs.saveTagInput.$refs.input.focus();
        });
      },

      handleInputConfirm() {

        let inputValue = this.inputValue;
        if (inputValue) {
          this.dynamicTags.push(inputValue);
          this.dynamicTags_id.push(inputValue);
        }

        this.tags_value = this.dynamicTags_id;
        //console.log(this.dynamicTags);
        this.inputVisible = false;
        this.inputValue = '';
      }
    }
  }
</script>