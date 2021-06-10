<template>
    <div class="row">
        <el-carousel :interval="5000" arrow="always" :height="height" >
            <el-carousel-item v-for="item in carousel_items" :key="item.id" >
                <el-image :src="item.image_url"   style="width:100%;" ></el-image>
            </el-carousel-item>
        </el-carousel>
    </div>
</template>
<style>

/*
.el-carousel__item h3 {
    color: #475669;
    font-size: 18px;
    opacity: 0.75;
    line-height: 300px;
    margin: 0;
}

.el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
}

.el-carousel__item:nth-child(2n + 1) {
    background-color: #d3dce6;
}
*/

@media(min-width:1024px){ 
.el-carousel__container{
    position: relative;
    
}
}
</style>
<script>
export default {
    data(){
        return {
            carousel_items:{},
            height: "",
        }
    },


    beforeMount:function(){
        if($(window).width()>=1024){ 
            this.height = $(window).width()*768/1920 + 'px';
        }else{
            //this.height = $(window).width()*900/920 + 'px';
        }
    },

    async mounted() {
        let that = this;
        let url = "/carousel_items";
        let submit_data = {};
        let config = {};
        let _this = this;
        let result = await axios.post(url, submit_data,config);
        if(result.status == "200"){
            if(result.data.code == "0"){
                //console.log(result.data.data);
                _this.carousel_items = result.data.data;
                //console.log(_this.carousel_items);
            }else{

            }
        }else{

        }
    }
};
</script>
