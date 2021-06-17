<template>
    <div class="">
        <quill-editor 
            name="content" 
            ref="myTextEditor" 
            v-model="content" 
            :options="editorOption" 
            @change="onEditorChange($event)"
            @ready="onEditorReady($event)"
            style="height: 250px; margin-bottom: 10px" >
        </quill-editor>
        <!-- <input type="file" hidden accept=".jpg,.png" ref="fileBtn" @change="handleChange" /> -->
    </div>
</template>

<script>
    export default {
        props:[
            "default_value"
        ],
        data(){
            return {
                content: "",
                editorOption: {},
                content_default_value:'',
            }
        },
        
        methods:{ 
            onEditorReady(e) { 
                if(this.default_value!=undefined){
                    this.content_default_value = this.default_value;
                
                }
                e.container.querySelector('.ql-blank').innerHTML = this.content_default_value;
            },

            onEditorChange($event){ 
                this.$emit("update",this.content);
                //console.log(this.content);
            },

          

            /*handleChange(e) {
                const files = Array.prototype.slice.call(e.target.files);
                if (!files) {
                    return;
                }
                console.log(files[0].name + files[0]);
                let formdata = new FormData();
                formdata.append("file_name", files[0].name);
                formdata.append("imgs", files[0]);
                console.log(formdata);
                
                axios({
                    url: '/articles_add',
                    method: 'post',
                    data: formdata,
                    //headers: {'client-identity': localStorage.getItem('session_id')}
                }).then((res) => {
                    //这里设置为空是为了联系上传同张图可以触发change事件
                    this.$refs.fileBtn.value = "";
                    if (res.data.code == 200) {
                        let selection = this.$refs.myQuillEditor.quill.getSelection();
                        //这里就是返回的图片地址，如果接口返回的不是可以访问的地址，要自己拼接
                        let imgUrl = this.$store.state.baseUrl + res.data.data; 
                        imgUrl = imgUrl.replace(/\\/g,"/")   
                        //获取quill的光标，插入图片 
                        this.$refs.myQuillEditor.quill.insertEmbed(selection != null ? selection.index : 0, 'image', imgUrl)                 
                        //插入完成后，光标往后移动一位 
                        this.$refs.myQuillEditor.quill.setSelection(selection.index + 1);
                    } 
                })
            }*/
        },
                    

        mounted() {
           
           
            /*if (this.$refs.myTextEditor) {
                this.$refs.myTextEditor.quill.getModule("toolbar").addHandler("image", this.imgHandler);
            }*/
        }
    }
</script>
