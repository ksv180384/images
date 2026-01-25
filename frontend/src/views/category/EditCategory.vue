<template>
  <div class="edit-category">
    <ul class="nav nav-tabs" id="controlTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button
          class="nav-link text-body fw-bold"
          type="button"
        >
          {{ category.title }}
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button
          class="nav-link active"
          id="images-tab"
          data-bs-toggle="tab"
          data-bs-target="#images"
          type="button"
          role="tab"
          aria-controls="home"
          aria-selected="true"
        >
          Изображения
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button
          class="nav-link"
          id="edit-tab"
          data-bs-toggle="tab"
          data-bs-target="#edit"
          type="button"
          role="tab"
          aria-controls="profile"
          aria-selected="false"
        >
          Редактировать
        </button>
      </li>
    </ul>

    <div class="tab-content">
      <div
        class="tab-pane fade show active"
        id="images"
        role="tabpanel"
        aria-labelledby="images-tab"
      >
        <AddImage
          :images-list="imagesStore.images"
          :tags-list="tagsList"
          @onSubmitImageForm="submitImageForm"
          @onDeleteImage="deleteImage"
          @onUpdateImage="updateImage"
          @onFilterChange="filterChange"
        />
      </div>
      <div class="tab-pane fade"
           id="edit"
           role="tabpanel"
           aria-labelledby="edit-tab"
      >
        <CategoryForm
          v-if="Object.keys(category).length"
          :category="category"
          @onSubmitForm="submitCategoryForm"
        />
      </div>
    </div>

    <UpScrollPage/>

  </div>
</template>

<script setup>

import { onMounted, onUnmounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { usePageStore } from '@/stores/page';
import { useImagesStore } from '@/stores/images';
import api from '@/services/api.ts';

import CategoryForm from '@/components/modules/CategoryForm.vue';
import AddImage from '@/components/modules/add_image/AddImage.vue';
import UpScrollPage from '@/components/UpScrollPage.vue';


const pageStore = usePageStore();
const imagesStore =  useImagesStore();
const route = useRoute();
const category = ref(pageStore?.data?.category || {});
const tagsList = ref(pageStore?.data?.tags || []);
const categoryId = parseInt(route.params.id);
const filter = ref({});

onMounted(() => {
  imagesStore.setImages(pageStore?.data?.images || []);
  imagesStore.setCategoryId(categoryId);
  if(imagesStore.category_id === categoryId){
    scrollToSavePosition();
  }
  document.addEventListener('scroll', onScroll);
});

onUnmounted(() => {
  document.removeEventListener('scroll', onScroll);
});

const submitCategoryForm = (categoryData) => {
  category.value = categoryData;
}

const onScroll = (e) => {
  localStorage.setItem(
    'images_data',
    JSON.stringify({ category_id: categoryId, scroll_top: window.scrollY })
  );
}

const scrollToSavePosition = () => {
  const imagesData = localStorage.getItem('images_data') ? JSON.parse(localStorage.getItem('images_data')) : null;

  if(imagesData?.category_id !== categoryId){
    return true;
  }

  window.scrollTo({
    top: imagesData.scroll_top,
    left: 0,
    behavior: 'instant',
  });

  return true;
}

const submitImageForm = (image) => {
  imagesStore.addImage(image);
}

const deleteImage = (imageId) => {
  imagesStore.deleteImage(imageId);
}

const updateImage = (updateImage) => {
  imagesStore.updateImage(updateImage);
}

const filterChange = async (filterData) => {
  if(filterData.no_date){
    filter.value = { ...filterData };
  }
  filter.value = { ...filter.value, ...filterData };
  // const resFilter = await imageFilter({ ...filter.value, category_id: categoryId });
  const resFilter = await api.image.imageFilter({ ...filter.value, category_id: categoryId });

  imagesStore.setImages(resFilter);
}

</script>

<style scoped>

</style>
