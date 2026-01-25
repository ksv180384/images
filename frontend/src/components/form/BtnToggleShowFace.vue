<template>
    <div class="btn-toggle-show-face" :title="title">
        <i v-if="isShow" class="bi bi-eye-slash"></i>
        <i v-else class="bi bi-eye"></i>
    </div>
</template>

<script setup>

import { toRef, computed } from 'vue';

const props = defineProps({
    isShowFace: Boolean,
});

const isShow = toRef(props, 'isShowFace');

const title = computed(() => {
    return isShow.value ? 'Скрыть лицо' : 'Показать лицо';
});
</script>

<style scoped>
.btn-toggle-show-face{
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    width: 30px;
    height: 30px;
    background-color: #ffffffad;
    color: #0f0f0f;
    border-radius: 6px;
    cursor: pointer;
}
</style>