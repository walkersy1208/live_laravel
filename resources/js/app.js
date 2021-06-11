/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//window.axios = require('axios');


//import CKEditor from '@ckeditor/ckeditor5-vue';
//Vue.use( CKEditor );


import VueQuillEditor from 'vue-quill-editor'
// require styles
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
Vue.use(VueQuillEditor)

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

//console.log(ElementUI);
Vue.use(ElementUI);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('follow-component', require('./components/followComponent.vue').default);
Vue.component('register-component', require('./components/RegisterComponent.vue').default);
Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('like-component', require('./components/LikeComponent.vue').default);
Vue.component('modal-component', require('./components/ModalComponent.vue').default);
Vue.component('edit-component', require('./components/EditComponent.vue').default);
Vue.component('message-component', require('./components/MessageComponent.vue').default);
Vue.component('comfirm-component', require('./components/ComfirmComponent.vue').default);
Vue.component('tags-component', require('./components/TagsComponent.vue').default);
Vue.component('tablelist-component', require('./components/admin/tablelistComponent.vue').default);
Vue.component('menu-component', require('./components/menuComponent.vue').default);
Vue.component('notify-component', require('./components/notifyComponent.vue').default);
Vue.component('carousel-component', require('./components/carouselComponent.vue').default);
Vue.component('select-component', require('./components/admin/selectComponent.vue').default);
Vue.component('upload-component', require('./components/admin/uploadComponent.vue').default);
Vue.component('xmodal-component', require('./components/uploadModalComponent.vue').default);
Vue.component('infinitescroll-component', require('./components/infiniteScrollComponent.vue').default);
Vue.component('swiper-component', require('./components/swiperComponent.vue').default);
Vue.component('article-component', require('./components/AddArticleComponent.vue').default);
Vue.component('editor-component', require('./components/editorComponent.vue').default);
Vue.component('search-component', require('./components/searchComponent.vue').default);
Vue.component('notificationlist-component', require('./components/NotificationListComponent').default);
Vue.component('sortbtn-component', require('./components/sortBtnComponent.vue').default);



Vue.component('spaheader-component', require('./components/spa/common/HeaderComponent.vue').default);
Vue.component('spalike-component', require('./components/spa/SpalikeComponent.vue').default);
Vue.component('spalogin-component', require('./components/spa/LoginComponent.vue').default);
//Vue.component('articledetail-component', require('./components/spa/pages/articleDetailComponent.vue').default);
Vue.component('spanotification-component', require('./components/spa/NotificationsComponent.vue').default);

//spa
//Vue.component('headercom', require('./components/spa/common/HeaderComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


//引入vue router
import VueRouter from 'vue-router';
Vue.use(VueRouter);
//注册router
import router from "./components/router/routes.js";

import store from "./components/store/index.js";

//const router = new VueRouter();
const app = new Vue({
    el: '#app',
    router: router,
    store,
});

