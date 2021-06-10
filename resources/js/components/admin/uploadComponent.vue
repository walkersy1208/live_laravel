<template>
    <div class="">
        <el-upload action="/api/upload" list-type="picture-card" :auto-upload="false"
        :file-list="fileList" name="file"
        :on-preview="handlePictureCardPreview"
        :on-change="(file, fileList) => {handleChange(file, fileList)}"
        ref="image_upload"
        >
            <i slot="default" class="el-icon-plus"></i>
            <div slot="file" slot-scope="{ file }">
                <img 
                    class="el-upload-list__item-thumbnail"
                    :src="file.url"
                    alt=""
                />
                <span class="el-upload-list__item-actions">
                    <span 
                        class="el-upload-list__item-preview"
                        @click="handlePictureCardPreview(file)"
                    >
                        <i class="el-icon-zoom-in"></i>
                    </span>
                    <span
                        v-if="!disabled"
                        class="el-upload-list__item-delete"
                        @click="handleDownload(file)"
                    >
                        <i class="el-icon-download"></i>
                    </span>
                    <span
                        v-if="!disabled"
                        class="el-upload-list__item-delete"
                        @click="handleRemove(file)"
                    >
                        <i class="el-icon-delete"></i>
                    </span>
                </span>
            </div>
        </el-upload>
        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="" />
        </el-dialog> 
    </div>
</template>
<style>
    /*.el-upload-list__item{
        transition: none;
    }*/
</style>
<script>
export default {
    props:["fileList_data","upload_id","upload_item_name"],
    data() {
        return {
            dialogImageUrl: "",
            dialogVisible: false,
            disabled: false,
            fileList: [],
            //old_file:"",
        };
    },
    methods: {
        handleRemove(file) {
            //console.log(file);
        },

        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.dialogVisible = true;
        },

        handleDownload(file) {
            console.log(file);
        },

        handlePictureCardPreview(){
        },
        handleUploadSuccess(){
        },

        handleChange(file,fileList){
            this.$emit("change_image",file,this.upload_item_name);
            this.fileList = [];
            let _this = this;
            this.fileList.push({name:"xxx",url:file.url});
            /*
            var reader = new FileReader();
            reader.readAsDataURL(file.raw);
            reader.onload = function (e) {
                let base64_image = e.target.result;
                var form = new FormData();
                form.append("file", base64_image);
                form.append("id",_this.upload_id);
                let config = {
                    header : {
                    'Content-Type' : 'multipart/form-data'
                    }
                }

                axios.post("/api/upload", form,config).then(function (res) {
                    let {status,dat} = res;
                    if(status ==="200"){

                    }
                });

            }*/

            //this.fileList_data = [];
            //this.fileList_data.push({name:"xxx",url:file.url});
            //this.file_list = [];
            //this.$refs.image_upload.clearFiles();
            //this.file_list.push({name:"xxx",url:file.url});
            //this.fileList = [];
            //this.fileList.push({name:"xxx",url:file.url});
        },
    },

    mounted() {
        
        //console.log(this.fileList_data);
        //this.$nextTick(function(){
        //this.file_list = this.fileList_data;
        //});
        
        this.fileList = this.fileList_data;

        //console.log(this.fileList);
    }
};
</script>
