export default {
    state:{
        auth:false,
        name:null,
        email:null,
        id:0,
    },

    mutations: {
        setAuth (state,payload) {
            state.auth  =  true;
            state.name  =  payload.name;
            state.email =  payload.email;
            state.id    =  payload.id;
            
        },

        userlogout(state){
            state.auth  =  false;
            state.name  =  "";
            state.email =  "";
            state.id    =  "";

           
        }
    },

    actions: {
        getUserInfo(context){
            let submit_data = {};
            let _this = this;
            let url = "/spa/user";
            let config = {};
            axios.post(url, submit_data,config).then(res => {
                let { status, data } = res;
                context.commit("setAuth",data.user);
            });
        },

        unsetUserAuth(context){
            context.commit("userlogout");
        }
    }
    
}