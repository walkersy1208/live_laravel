<template>
    <div class="">
        <div class="row">

            <message-component
                v-if="success_response"
                :status="status"
                :message="msg"
            ></message-component>

            <div>
                <edit-component
                    title="提示信息"
                    :is_input="true"
                    :fields="fields"
                    msg="修改成功"
                    status="success"
                    :request_url="request_url"
                    :edit_btn="false"
                  
                >
                </edit-component>
            </div> 
            <!-- <div>
                <article-component
                        type="edit" 
                        title="提示信息" 
                        :fields="fields"
                         
                        msg="添加成功" 
                        status="success"
                        request_url="/api/edit_admin/"
                        redirect_url=""
                        btn_style_type=""
                    >
                </article-component>
            </div> --> 
        </div>
        
        <el-table
            row-key="id"
            :data="
                tableData.filter(
                    data =>
                        !search ||
                        data.name.toLowerCase().includes(search.toLowerCase())
                )
            "
            style="width: 100%"
        >
            <el-table-column label="Name" prop="name" sortable  width="150" > </el-table-column>
            <el-table-column label="Date" prop="created_at"  sortable> </el-table-column>
            <el-table-column label="Is Visible" prop="is_visible" sortable width="150">
                <template slot-scope="scope">
                    <el-switch
                       
                        @change="
                            switchChange(scope.row.is_visible, scope.row.id)
                        "
                        v-model="scope.row.is_visible"
                        inactive-color="#cccc"
                    >
                    </el-switch>
                </template>
            </el-table-column>
            <el-table-column label="Image Url" prop="image_url">
                <template slot-scope="scope">
                    <el-image
                        :src="scope.row.image_url"
                        :preview-src-list="[scope.row.image_url]"
                        style="width: 150px;"
                    ></el-image>
                </template>
            </el-table-column>

            <el-table-column label="Mobile Image Url" prop="image_url">
                <template slot-scope="scope">
                    <el-image
                        :src="scope.row.mobile_image_url"
                        :preview-src-list="[scope.row.mobile_image_url]"
                        style="width: 150px;"
                    ></el-image>
                </template>
            </el-table-column>

            <el-table-column align="right">
                <template slot="header" slot-scope="scope">
                    <el-input
                        v-model="search"
                        size="mini"
                        placeholder="输入关键字搜索"
                    />
                </template>
                <template slot-scope="scope">
                    
                    <button type="button" class="btn btn-primary edit-btn" style="min-width:80px;">
                        <div  @click="handleEdit(scope.$index, scope.row)" >
                            <i  class='el-icon-edit' ></i>
                        </div>
                    </button>
                    
                    <button type="button" class="btn btn-danger del_btn"  style="min-width:80px;">
                        <div @click="handleDelete(scope.$index, scope.row)"  style="color:#fff;" ><i class="el-icon-delete"></i></div>
                    </button>
                </template>
            </el-table-column>
        </el-table>
        <!-- </draggable> -->
    </div>
</template>

<script>
//import draggable from 'vuedraggable'
import Sortable from "sortablejs";
export default {
    components: {
        //draggable,
    },
    props: ["carousel_data"],
    data() {
        return {
            request_url: "/api/upload",
            id: "",
            success_response: "",
            status: "",
            msg: "",
            fields: [],
            tableData: [],
            search: "",
            value: true
        };
    },

    methods: {
        rowDrop() {
           
            const tbody = document.querySelector(
                ".el-table__body-wrapper tbody"
            );
            const _this = this;
            Sortable.create(tbody, {
                onStart: () => {
                    
                },
                onEnd({ newIndex, oldIndex }) {
                    
                    let tmp = [];
                    let newIndex_id = _this.tableData[newIndex].id;
                    let newIndex_display_priority = _this.tableData[newIndex].display_priority;
                    
                    let oldIndex_id = _this.tableData[oldIndex].id;
                    let oldIndex_display_priority = _this.tableData[oldIndex].display_priority;
                    //互换display_priority
                    //submit_data[oldIndex_id] = newIndex_display_priority;
                    //submit_data[newIndex_id] = oldIndex_display_priority;
                    tmp=[
                        {"id":oldIndex_id,"priority":newIndex_display_priority},
                        {"id":newIndex_id,"priority":oldIndex_display_priority}
                    ]

                    let submit_data = {
                        "sort_data":tmp
                    };
                       
                    let url = "/api/sort_table";
                    axios.post(url, submit_data).then(res => {
                        let { status, data } = res;
                        if (status == "200") {
                            //_this.value = false;
                            if(data.code === "0"){
                                setTimeout(function(){
                                    window.location.reload();
                                }, 700);
                            }
                        }
                    });
                    //do something here....
                    /*const currRow = _this.tableData.splice(oldIndex, 1)[0];
                    _this.tableData.splice(newIndex, 0, currRow);*/
                }
            });
        },
        //列拖拽
        columnDrop() {
            const wrapperTr = document.querySelector(
                ".el-table__header-wrapper tr"
            );
            this.sortable = Sortable.create(wrapperTr, {
                animation: 180,
                delay: 0,
                onEnd: evt => {
                    const oldItem = this.dropCol[evt.oldIndex];
                    this.dropCol.splice(evt.oldIndex, 1);
                    this.dropCol.splice(evt.newIndex, 0, oldItem);
                }
            });
        },
        handleEdit(index, row) {

            this.$children[0].dialogVisible = true;
            if (row["is_visible"]) {
                row["is_visible"] = "Y";
            } else {
                row["is_visible"] = "N";
            }

            this.fields = row;
            let arr = [];
            for (let i in row) {
                let obj = {};
                if (i == "name") {
                    obj.ph = "请输入Carousel Name";
                    obj.type = "text";
                } else if (i == "id") {
                    continue;
                } else if (i == "url") {
                    obj.ph = "请输入Carousel Url";
                    obj.type = "text";
                } else if (i == "is_visible") {
                    obj.ph = "请输入Carousel 是否显示";
                    obj.type = "radio";
                } else if (i == "image_url") {
                    obj.ph = "";
                    obj.type = "upload_image";
                } else if (i == "mobile_image_url") {
                    obj.ph = "";
                    obj.type = "mobile_upload_image";
                }else if(i == "display_priority"){
                    obj.ph = "";
                    obj.type = "hidden";
                }

                obj.id = row["id"];
                obj.val = row[i];
                obj.name = i;
                arr.push(obj);
            }

            this.fields = arr;
            //console.log(this.fields);
        },

        //Change Switch Status
        switchChange(status, id) {
            if (status) {
                status = "Y";
            } else {
                status = "N";
            }
            let url = "/api/changeSwitchStatus";
            let submit_data = {
                is_visible: status,
                id: id
            };

            let config = {};
            let _this = this;
            axios.post(url, submit_data, config).then(res => {
                let { status, data } = res;
                if (status == "200") {
                    console.log(data);
                }
            });
        },

        handleDelete(index, row) {
            //send axios to delete
            let _this = this;
            this.$confirm("此操作将永久删除该文件, 是否继续?", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            })
                .then(() => {
                    let config = {};
                    let submit_data = {
                        id: row["id"]
                    };

                    let url = "/api/del_carousel";
                    axios.post(url, submit_data, config).then(res => {
                        let { status, data } = res;
                        if (status == "200") {
                            if (data.code === "0") {
                                setTimeout(function() {
                                    window.location.reload();
                                }, 700);
                            }
                        }
                    });

                    this.$message({
                        type: "success",
                        message: "删除成功!"
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: "已取消删除"
                    });
                });
        }
    },

    mounted() {

        this.rowDrop();
        this.columnDrop();
        this.carousel_data.map(function(i, v) {
            if (i.is_visible == "N") {
                i.is_visible = false;
            } else if (i.is_visible == "Y") {
                i.is_visible = true;
            }
            return i;
        });
        //console.log(this.carousel_data);
        this.tableData = this.carousel_data;
    }
};
</script>
