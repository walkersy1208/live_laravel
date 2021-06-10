<template>
    <el-select
        v-model="value"
        multiple
        filterable
        allow-create
        remote
        default-first-option
        reserve-keyword
        placeholder="请输入关键词"
        :remote-method="remoteMethod"
        :loading="loading"
        @change="handlerChange"
    >
        <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
        >
        </el-option>
    </el-select>
</template>
<style>
.el-select {
    display: block;
    position: relative;
}
</style>

<script>
export default {
    props: ["data_list"],
    data() {
        return {
            options: [],
            value: [],
            list: [],
            loading: false,
            states: [],
            tags: []
        };
    },

    mounted() {
        let url = "/article_tags";
        let config = "";
        let submit_data = {};
        let _this = this;
        axios.post(url, submit_data, config).then(res => {
            let { status, data } = res;
            if (data.code == "0") {
                let tags = data.all_tags;
                _this.states = tags;
                _this.states = tags;
                _this.list = tags.map(item => {
                    return { value: `${item.name}`, label: `${item.name}` };
                });
            }
        });

        if(this.data_list){
            let list  = JSON.parse(this.data_list);
            let v=list.map((item)=>{
                return item.name;
            });
            this.value = v;
            //tigger handlerChange event & send value
            this.handlerChange();
        }
    },

    methods: {
        handlerChange() {
            if (this.value) {
                this.$emit("changInput", this.value);
            }
        },

        remoteMethod(query) {
            if (query !== "") {
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                    this.options = this.list.filter(item => {
                        return (
                            item.label
                                .toLowerCase()
                                .indexOf(query.toLowerCase()) > -1
                        );
                    });
                }, 200);
            } else {
                this.options = [];
            }
        }
    }
};
</script>
