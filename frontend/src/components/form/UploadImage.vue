<template>
    <div class="upload-image upload rounded mt-4"
         @drop="addImage"
         @dragover="beforeAddImage"
         @dragleave="beforeAddImage"
    >
        <div v-if="img" class="upload-img-container">
            <div class="image-item-control-block">
                <BtnDelete @click="removeImg" />
            </div>
            <img :src="img" alt="Изображение"/>
        </div>
        <i v-else class="bi bi-cloud-arrow-up"></i>

        <input type="file" ref="inputImg" @change="onChangeFileInput"/>

        <div v-if="isLoad" class="preloader">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="preloader-info">
                Поиск похожих изображений...
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, defineEmits, toRef } from 'vue';

import BtnDelete from '@/components/form/BtnDelete.vue';

const props = defineProps({
    imagePreview: String,
    isLoad: Boolean,
});

const img = toRef(props, 'imagePreview');
const isLoad = toRef(props, 'isLoad');
const inputImg = ref(null);
const emits = defineEmits(['onChangeImage']);

const onChangeFileInput = (e) => {
    emits('onChangeImage', e.target.files);
    inputImg.value = [];
}

const addImage = (e) => {
    e.preventDefault();
    const dt = e.dataTransfer
    inputImg.value.files = dt.files;

    const changeEvent = new Event('change');
    inputImg.value.dispatchEvent(changeEvent);
}

const removeImg = () => {
    emits('onChangeImage', []);
    inputImg.value = [];
}

const beforeAddImage = (e) => {
    e.preventDefault();
}

</script>

<style scoped>
.upload{
    position: relative;
    display: flex;
    width: 200px;
    min-height: 260px;
    align-items: center;
    justify-content: center;
    font-size: 76px;
    border: 2px dashed;
}

.upload input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    z-index: 1;
}

.upload img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preloader{
    position: absolute;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #ffffffdb;
    font-size: 26px;
}

.preloader-info{
    font-size: 16px;
}

</style>