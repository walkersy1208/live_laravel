<template>
   <carousel :autoplay="true" :nav="false" :items="1" v-if="carousel_items.length>0" :responsive="{0:{items:1,nav:false},600:{items:1,nav:false}}">
      <div v-for="item in carousel_items" :key="item.url">
            <img  :src="item.image_url" style="width:100%;" class="d-none d-sm-block">
            <img  :src="item.mobile_image_url" style="width:100%;" class="d-block d-sm-none">
      </div>
     </carousel>
</template>

<script>

import carousel from 'vue-owl-carousel'
export default {
    data(){
      return {
          carousel_items:"",
      }
    },
    components: { carousel },
    async mounted() {
        let that = this;
        let url = "/carousel_items";
        let submit_data = {};
        let config = {};
        let _this = this;
        let result = await axios.post(url, submit_data,config);
        if(result.status == "200"){
            if(result.data.code == "0"){
                _this.carousel_items = result.data.data;
                //console.log(_this.carousel_items);
            }else{

            }
        }else{

        }
    }
}

</script>