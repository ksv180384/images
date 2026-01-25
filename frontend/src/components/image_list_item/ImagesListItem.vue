<template>
  <div ref="refImagesListItem" class="images-list-item">
    <div class="position-relative rounded overflow-hidden">

      <router-link
        :to="{ name: 'admin.category.image', params: { category_id: img.category_id, id: img.id } }"
      >
        <img :style="isClassVisibleImage" :src="image.path_preview_full">
      </router-link>

      <div v-if="!image.is_features" class="is-not-search">
        нет в поиске
      </div>

      <div class="size-image">
        {{ image.width }} x {{ image.height }}
      </div>

      <ImageItemControlBlock
        class="image-item-control-container"
        @onDeleteImage="showConfirmDelete"
        @onToggleShowFace="toggleShowFace"
        @onShowTagsControl="showTagsControl"
        @onShowCreateDateImage="showCreateDateImage"
        @onShowSimilar="showSimilar"
      />

      <ImageItemDeleteConfirm
        v-if="isShowConfirmDelete"
        @onDelete="deleteItem"
        @onCansel="hideConfirmDelete"
      />

      <div
        v-if="isShowFace && img.path_face"
        :style="isClassVisibleImage"
        class="image-face"
      >
        <img :src="img.path_face">
      </div>

      <ImageItemCreateDate
        v-if="isShowCreateDateImage"
        :date-create="img.image_created_at"
        :is-load="isLoadSaveCreateDateImage"
        @onSave="saveCreateDateImage"
        @onCansel="hideCreateDateImage"
      />

      <ImageItemTagsControl
        v-if="isShowTagsControl"
        :image-id="img.id"
        :tags-list="tags"
        @onCancel="hideTagsControl"
      />

    </div>
  </div>

  <template v-if="similarImages.length">
    <BtnMini
      @click="closeSimilar"
      class="close-button"
      icon="x-lg"
      title="Закрыть"
    />
    <div class="similar-images-container">
      <template v-if="similarImages">
        <ImagesListItem
          v-for="similarImageItem in similarImages"
          :key="`similar-${similarImageItem.id}`"
          :image="similarImageItem"
          :tags-list="tags"
          @onDeleteImage="deleteItem"
          @onUpdateImage="saveCreateDateImage"
        />
      </template>
    </div>
  </template>
</template>

<script setup>

import { ref, toRef, defineEmits, onMounted, computed } from 'vue';
import api from '@/services/api.ts';

import ImageItemControlBlock from '@/components/image_list_item/ImageItemControlBlock.vue';
import ImageItemTagsControl from '@/components/modules/ImageItemTagsControl.vue';
import ImageItemDeleteConfirm from '@/components/image_list_item/ImageItemDeleteConfirm.vue';
import ImageItemCreateDate from '@/components/image_list_item/ImageItemCreateDate.vue';
import BtnMini from '@/components/form/BtnMini.vue';

const props = defineProps({
  image: Object,
  tagsList: { type: Array, required: false, default: [] }
});

const emits = defineEmits(['onDeleteImage', 'onUpdateImage']);
const img = toRef(props, 'image');
const tags = toRef(props, 'tagsList');
const isShowImage = ref(false);
const similarImages = ref([]);
const isShowFace = ref(true);
const isShowConfirmDelete = ref(false);
const isShowTagsControl = ref(false);
const isShowCreateDateImage = ref(false);
const isLoadSaveCreateDateImage = ref(false);
const refImagesListItem = ref(null);

onMounted(() => {
  const observer = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        isShowImage.value = true;
        //console.log('Element is visible in the viewport');
        // Do something when the element is visible in the viewport
      } else {
        isShowImage.value = false;
      }
    },
    { rootMargin: '10px', threshold: 0.1 } // Threshold is set to 50%
  );

  observer.observe(refImagesListItem.value); // Observe the mounted element

});

const isClassVisibleImage = computed(() => {
  return isShowImage.value ? {'visibility': 'visible'} : {'visibility': 'hidden'};
});

const showConfirmDelete = async () => {
  isShowConfirmDelete.value = true;
}

const hideConfirmDelete = async () => {
  isShowConfirmDelete.value = false;
}

const deleteItem = async () => {
  isShowConfirmDelete.value = false;
  try{
    await api.image.deleteImage(img.value.id);
    emits('onDeleteImage', img.value.id);
  } catch (e) {
    console.error(e);
  } finally {

  }
}

const toggleShowFace = (val) => {
  isShowFace.value = val;
}

const showTagsControl = () => {
  isShowTagsControl.value = true;
}

const hideTagsControl = () => {
  isShowTagsControl.value = false;
}

const showCreateDateImage = () => {
  isShowCreateDateImage.value = true;
}

const hideCreateDateImage = () => {
  isShowCreateDateImage.value = false;
}

const saveCreateDateImage = async (date) => {
  isLoadSaveCreateDateImage.value = true;
  try{
    const resImage = await api.image.saveDateCreateImage(img.value.id, date);
    emits('onUpdateImage', resImage);
  } catch (e) {
    console.error(e);
  } finally {
    isLoadSaveCreateDateImage.value = false;
  }
}

// Показываем похожие изображения
const showSimilar = async () => {
  similarImages.value = await api.image.getShowSimilarRequest(img.value.id);
}

const closeSimilar = () => {
  similarImages.value = [];
}
</script>

<style scoped>
.images-list-item{
  position: relative;
  display: inline-block;
  overflow: hidden;
}

.images-list-item > a{
  position: relative;
  display: inline-block;
  border-radius: 6px;
  overflow: hidden;
}

.images-list-item > img{
  height: 320px;
  object-fit: contain;
}

.image-item-control-container{
  display: none;
}

.images-list-item:hover .image-item-control-container{
  display: flex;
}

.size-image{
  position: absolute;
  display: none;
  right: 0;
  bottom: 0;
  color: #e9ecef;
  background-color: #0f0f0f75;
  border-radius: 6px 0 0 0;
  padding: 2px 6px;
}

.images-list-item:hover .size-image{
  display: block;
}

.image-face{
  display: inline-block;
  position: absolute;
  top: 4px;
  left: 4px;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  overflow: hidden;
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
  border: 2px solid #fff;
}

.image-face img{
  width: 60px;
  object-fit: cover;
}

.is-not-search{
  position: absolute;
  left: 0;
  bottom: 0;
  color: #e9ecef;
  background-color: #0f0f0f75;
  border-radius: 0 6px 0 0;
  padding: 2px 6px;
}

.similar-images-container{
  position: fixed;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: space-evenly;
  gap: 10px;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  overflow: auto;
  background-color: #fff;
  z-index: 2;
}

.close-button{
  position: fixed;
  top: 4px;
  right: 4px;
  background-color: #fff;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  z-index: 3;
}
</style>