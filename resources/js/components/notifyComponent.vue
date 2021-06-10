<template>
    <div class="container">
        <el-badge class="item">
            <el-button size="small">所有通知</el-button>
        </el-badge>
        <el-badge :value="3" class="item">
            <el-button size="small">未阅读通知</el-button>
        </el-badge>
    </div>
</template>
<style>
.item {
    margin-top: 10px;
    margin-right: 40px;
}

.mark,
.mark {
    background: #fff;
}
</style>
<script>
export default {
    mounted() {
        console.log("Component nofify.");
    }
};
</script>
