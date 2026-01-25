<template>
  <el-button type="danger" :icon="Delete" circle/>
</template>

<script setup>
import { Delete } from '@element-plus/icons-vue';
</script>

<style scoped>
.remove{
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    width: 30px;
    height: 30px;
    background-color: #ff0000ab;
    border-radius: 6px;
    color: #fff;
    cursor: pointer;
}
</style>