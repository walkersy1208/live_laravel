<template>
    <div class="container">
        <div>
            <top-menu></top-menu>
        </div>
        <div> 
            <router-view></router-view>
        </div>
     </div>
    
 </template>

<script>
    import topMenu from "../common/HeaderComponent.vue";
    console.log(topMenu);
    export default {
        componts:{
            topMenu
        },

        mounted() {

        }
    }
</script>
