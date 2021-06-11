import VueRouter from 'vue-router';
//import about from '../../components/spa/pages/aboutComponent.vue';
//import home from '../../components/spa/pages/homeComponent.vue';
//import register from '../../components/spa/pages/registerCompnent.vue';


export default new VueRouter({
    mode: 'history',
    routes: [
        {   
            path: '/spa/login/',
            name:'login',
            component:()=>import('../../components/spa/LoginComponent.vue')
            
        },

        {   
            path: '/spa/index',
            name:'home',
            component: ()=>import('../../components/spa/pages/homeComponent.vue')
        },

        {   
            path: '/spa/article_detail/:id',
            name:'detail',
            component: ()=>import('../../components/spa/pages/articleDetailComponent.vue')
        },

        {   
            path: '/spa/read_all_notifications',
            name:'notifications',
            component: ()=>import('../../components/spa/NotificationsComponent.vue'),
            props: { action: "all_message"}
        },

        {   
            path: '/spa/read_notifications',
            name:'unread_notifications',
            component: ()=>import('../../components/spa/UnreadNotificationsComponent.vue'),
            props: { action: "unread_message"}
        },

        

        
    ]
});