<template>
  <div class="image-item-tags-control">
    <div class="content-container">
      <form @submit.prevent="addNewTag" class="form-search">
        <input
            v-model="newTagName"
            ref="inputSearch"
            type="text"
            placeholder="Введите название тега"
            title="Введите название тега"
            required
        />
        <BtnMini
            v-if="newTagName.length"
            @click="addNewTag"
            class="add-tag-btn"
            icon="plus-square"
            title="Добавить ноый тег"
        />
      </form>
      <div class="tags-list">
        <template v-if="showTags.length">
          <label
              v-for="tag in showTags"
              class="tags-list-item"
              :key="tag.id"
              :from="`tagItem_${tag.id}`"
          >
            <input
                v-model="selectedTags"
                type="checkbox"
                name="tags[]"
                :id="`tagItem_${tag.id}`"
                :value="tag.id"
            />
            <div class="tags-list-item-text">
              {{ tag.title }}
            </div>
          </label>
        </template>
      </div>
    </div>
    <div class="control-block">
      <BtnStandart
          @click="addTagsToImage"
          :mini="true"
          :outline="true"
          style-type="success"
          :is-load="loadingAddTagsToImage"
      >
        Сохранить
      </BtnStandart>
      <BtnStandart
          @click="cancel"
          :mini="true"
          :outline="true"
          style-type="danger"
      >
        Закрыть
      </BtnStandart>
    </div>
  </div>
</template>

<script setup>
import { ref, toRef, defineEmits, onMounted, computed } from 'vue';
// import {addTag, attachTagsToImage, getAttachTagsToImage} from '@/services/api_admin';
import api from '@/services/api.ts';

import BtnStandart from '@/components/form/BtnStandart.vue';
import BtnMini from '@/components/form/BtnMini.vue';

const props = defineProps({
    tagsList: { type: Array, required: false, default: [] },
    imageId: { type: Number, required: true }
});
const emits = defineEmits(['onCancel']);
const inputSearch = ref(null);
const tags = toRef(props, 'tagsList');
const newTagName = ref('');
const selectedTags = ref([]);
const loadAddNewTag = ref(false);
const loadingAddTagsToImage = ref(false);

onMounted(() => {
    inputSearch.value.focus();
    getTags(props.imageId);
});

const showTags = computed(() => {
    return tags.value.filter((tag) => {
        return tag.title
            .toLowerCase()
            .indexOf(newTagName.value.toLowerCase()) != -1
    })
});

const cancel = () => {
    emits('onCancel');
}

const addNewTag = async () => {
    if(loadAddNewTag.value){
        return true;
    }
    loadAddNewTag.value = true;
    try{
        const resAddTag = await api.tag.addTag({ title: newTagName.value });
        tags.value.unshift(resAddTag);
    } catch (e) {
        console.error(e);
    } finally {
        newTagName.value = '';
        loadAddNewTag.value = false;
    }
}

const getTags = async (imageId) => {
    const resGetAttachTagsToImage = await api.image.getAttachTagsToImage(imageId);
    selectedTags.value = resGetAttachTagsToImage.map((item) => { return item.id });
}

const addTagsToImage = async () => {
  loadingAddTagsToImage.value = true;
  try {
    const resAttachTagsToImage = await api.tag.attachTagsToImage({ image_id: props.imageId, tags: selectedTags.value });
    cancel();
  } catch (e) {
    console.error(e);
  } finally {
    loadingAddTagsToImage.value = false;
  }
}
</script>

<style scoped>
.image-item-tags-control{
    position: absolute;
    display: flex;
    flex-direction: column;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 6px;
    background-color: #e9ecef;
    z-index: 2;
    cursor: default;
}

.content-container{
    flex: 1;
    max-height: calc(100% - 42px);
}

.form-search{
    position: relative;
}

.form-search > input {
    width: 100%;
    padding: 5px 38px 5px 10px;
    border-radius: 6px;
}

.form-search .add-tag-btn{
    position: absolute;
    top: 50%;
    right: 4px;
    transform: translate(0, -50%);
    background-color: #e5e5e5;
    border-radius: 4px;
}

.tags-list{
    display: flex;
    flex-direction: column;
    margin-top: 10px;
    gap: 4px;
    overflow: auto;
    max-height: calc(100% - 50px);
    padding: 0 6px;
}

.tags-list-item{
    position: relative;
    border-radius: 4px;
    border: 1px solid #ccc;
    background-color: #fff;
    cursor: pointer;
}

.tags-list-item-text{
    padding: 4px 8px;
}

.tags-list-item > input:checked + .tags-list-item-text{
    background-color: var(--bs-link-hover-color);
    color: #fff;
}

.tags-list-item input{
    display: none;
}

.control-block{
    display: flex;
    flex-direction: row;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
}
</style>