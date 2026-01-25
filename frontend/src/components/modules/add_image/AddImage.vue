<template>
  <div class="add-images d-flex flex-column">

    <div ref="refTriggerFixedBlock"></div>

    <div
        class="d-flex gap-2 px-2 py-4"
        :class="{ 'fixed-control-block': isFixedControlBlock }"
    >
      <div class="images-list flex-grow-1 relative">

        <div v-if="similarImages.length" class="similar-images-container">
          <h3>Похожие изображения</h3>
          <div class="images-list flex-grow-1 flex-grow-1 flex-shrink-1">
            <ImagesListItem
              v-for="image in similarImages"
              :key="image.id"
              :image="image"
              :tags-list="tags"
              @onDeleteImage="deleteImage"
              @onUpdateImage="updateImage"
            />
          </div>
          <hr/>
        </div>

        <template v-if="images.length">
          <ImagesListItem
            v-for="image in images"
            :key="image.id"
            :image="image"
            :tags-list="tags"
            @onDeleteImage="deleteImage"
            @onUpdateImage="updateImage"
          />
        </template>
      </div>

      <div class="control-container">
        <div class="control-block">
          <div>
            <form @submit="onSubmitForm">
              <div>
                <el-button
                    type="primary"
                    :disabled="loadCreateImage"
                    :loading="loadCreateImage"
                    plain
                >
                  Добавить
                </el-button>
              </div>

              <UploadImage
                  :is-load="loadSearchImage"
                  :image-preview="imagePreview"
                  @onChangeImage="changeImage"
              />
            </form>

            <FilterDateCreate
                class="my-2"
                @onChange="changeDateRange"
                @onChangeNoDate="changeNoDate"
            />
          </div>

          <div class="filter-tags-container mt-3">
            <FilterTag
              :tags-list="tags"
              @onChange="changeFilterTag"
            />
          </div>

        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

import {onMounted, ref, toRef, defineEmits, nextTick} from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api.js';

import { IImage } from '@/interfaces/IImage';

import UploadImage from '@/components/form/UploadImage.vue';
import ImagesListItem from '@/components/image_list_item/ImagesListItem.vue';
import FilterDateCreate from '@/components/modules/add_image/FilterDateCreate.vue';
import FilterTag from '@/components/modules/add_image/FilterTag.vue';


const props = defineProps({
  imagesList: { type: Array, default: [] },
  tagsList: { type: Array, default: [] },
});

const route = useRoute();
const emits = defineEmits(['onSubmitImageForm', 'onDeleteImage', 'onUpdateImage', 'onFilterChange']);
const formData = new FormData();

const refTriggerFixedBlock = ref(null);
const imagePreview = ref<string | ArrayBuffer | null>('');
const similarImages = ref<IImage[]>([]);
const loadCreateImage = ref(false);
const loadSearchImage = ref(false);
const images = toRef(props, 'imagesList');
const tags = toRef(props, 'tagsList');
const isFixedControlBlock = ref(false);

onMounted(() => {
  formData.append('category_id', route.params.id);
});

const onSubmitForm = async (e) => {
  e.preventDefault();
  if(loadCreateImage.value){
    return true;
  }
  loadCreateImage.value = true;
  try{
    // const image = await addImage(formData);
    const image = await api.image.addImage<IImage>(formData);
    formData.delete('files[]');
    imagePreview.value = '';
    similarImages.value = [];
    emits('onSubmitImageForm', image);
  } catch (e) {
    console.error(e);
  }finally {
    loadCreateImage.value = false;
  }
}

const changeImage = (files) => {
  showPreview(files);
  similarImages.value = [];
  formData.delete('files[]');
  for(let file of files){
    formData.append('files[]', file);
  }
}

const showPreview = (files) => {
  const file = files[0];
  if(!file){
    imagePreview.value = '';
    return true;
  }
  const reader = new FileReader();

  reader.onloadend = async function() {
    imagePreview.value = reader.result;
    loadSearchImage.value = true;
    try{
      const resSearchByPhoto = await api.image_control_api.searchByPhoto<IImage[]>(route.params.id, reader.result);
      similarImages.value = resSearchByPhoto;
    } catch(e) {
      console.error(e);
    } finally {
      loadSearchImage.value = false
    }
  }
  reader.readAsDataURL(file);
}

const deleteImage = (imageId) => {
  similarImages.value = similarImages.value.filter((item) => { return item.id !== imageId; });
  emits('onDeleteImage', imageId);
}

const updateImage = (updateImage) => {
  emits('onUpdateImage', updateImage);
}

const changeFilterTag = (selectTagsList) => {
  const exist = selectTagsList.filter(item => item.active === 'exist').map((item) => item.id);
  const notExist = selectTagsList.filter(item => item.active === 'not-exist').map((item) => item.id);

  emits('onFilterChange', { exist: exist, not_exist: notExist });
}

const changeDateRange = (val) => {
  emits('onFilterChange', { date_range: val });
}

const changeNoDate = (val) => {
  emits('onFilterChange', { no_date: val });
}

</script>

<style scoped>

.images-list{
  display: flex;
  justify-content: space-between;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 6px;
}

.control-container{
  width: 200px;
  text-align: center;
}

.control-container > .control-block{
  position: sticky;
  top: 10px;
  height: calc(100vh - 20px);
  display: flex;
  flex-direction: column;
}

.filter-tags-container{
  flex: 1 0 auto;
}

.similar-images-container{
  min-width: 100%;
  text-align: center;
  background-color: #ebebeb;
  margin-top: -24px;
  margin-left: -8px;
}

.similar-images-container h2{
  padding-bottom: 18px;
  padding-top: 18px;
}

.similar-images-block{
  display: flex;
  justify-content: space-evenly;
  flex-wrap: wrap;
  gap: 6px
}

.fixed-control-block .images-list{
  flex: 1 0;
}

.fixed-control-block .control-container{
  /*position: fixed !important;*/
  /*height: 100vh;*/
  position: relative;
}

.fixed-control-block .control-block{
  position: fixed !important;
  height: calc(100vh - 40px);
  width: 200px;
}

.fixed-control-block .filter-tags-container{
  height: calc(100vh - 450px);
}
</style>