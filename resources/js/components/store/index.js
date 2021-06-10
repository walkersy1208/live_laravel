import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

import AuthUser from "./modules/auth_user";
export default new Vuex.Store({
    modules: {
       AuthUser,
    }
   
})
 
