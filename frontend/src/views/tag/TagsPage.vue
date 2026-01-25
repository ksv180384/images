<template>
  <div class="tags-page">
    <div class="d-flex px-2 py-4 gap-2">
      <div class="images-container">

        <el-select
          v-model="selectCategory"
          size="large"
          filterable
          clearable
          placeholder="Выберите категорию"
          @change="loadCategory"
          @clear="loadCategory"
        >
          <el-option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
              :label="category.title"
          />
        </el-select>

        <div class="images-block">
          <template v-if="images.length">
            <ImageItem
              v-for="image in images"
              :key="image.id"
              v-model="selectImages"
              :image="image"
              @onActivate="activateImage"
            />
          </template>
        </div>
      </div>

      <div class="tags-container">

        <TagsControl
          :tags="tags"
          :is-load-save="isLoadSaveAttach"
          @onDeleteTag="deleteTag"
          @onActivateTag="activateTag"
          @onSave="attach"
        />

      </div>
    </div>

    <UpScrollPage/>

  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePageStore } from '@/stores/page';
import api from '@/services/api';

import TagsControl from '@/components/modules/tags_control/TagsControl.vue';
import ImageItem from '@/components/tag/ImageItem.vue';
import UpScrollPage from '@/components/UpScrollPage.vue';

const pageStore = usePageStore();
const tags = ref(pageStore?.data?.tags || []);
const categories = ref(pageStore?.data?.categories || []);
const images = ref([]);
const selectCategory = ref(null);
const selectTags = ref([]);
const selectImages = ref([]);
const isLoadSaveAttach = ref(false);

const loadCategory = async () => {
  if(!selectCategory.value){
    images.value = [];
    return;
  }
  try{
    images.value = await api.image.getImagesByCategoryId(selectCategory.value);
  }catch (e) {
    console.error(e)
  }finally {

  }
}

const activateTag = async (tagId, isActivate) => {
  if(isActivate){
    selectTags.value.push(tagId);
  }else{
    selectTags.value = selectTags.value.filter((item) => item !== tagId);
  }

  const res = await api.tag.getImagesByTags(selectCategory.value, selectTags.value);
  selectImages.value = res.images_ids
}

const activateImage = (imageId, isActivate) => {
  if(isActivate){
    selectImages.value.push(imageId);
  }else{
    selectImages.value = selectImages.value.filter((item) => item !== imageId);
  }
}

const attach = async () => {
  isLoadSaveAttach.value = true;
  try{
    await api.tag.attachTags(selectTags.value, selectImages.value);
  } catch (e) {
    console.log(e);
  } finally {
    isLoadSaveAttach.value = false;
  }
}

const deleteTag = (id) => {
  tags.value = tags.value.filter((item) => item.id !== id);
}
</script>

<style scoped>
.images-container{
  flex: 1;
}

.images-block{
  display: flex;
  justify-content: space-evenly;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 20px;
}

.tags-container{
  position: sticky;
  top: 20px;
  height: calc(100vh - 120px);
  width: 260px;
  overflow: hidden;
}
</style>