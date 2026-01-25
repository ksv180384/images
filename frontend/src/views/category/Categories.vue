<script setup lang="ts">
import { onMounted, onUnmounted, ref, computed, Ref } from 'vue';
import { usePageStore } from '@/stores/page';

import { ICategory } from '@/interfaces/ICategory';

import CategoryItem from '@/components/CategoryCard.vue';

const pageStore = usePageStore();
const categories: Ref<ICategory[]> = ref(pageStore?.data?.categories || []);
const widthWindow: Ref<number>  = ref(0);

const categoriesColumnsList = computed(() => {
  let columns: number = 2;
  // В зависимости от ширины окна браузера определяем количество колонок
  if(widthWindow.value < 1140){
    columns = 1;
  }
  return cardsSort(columns);
});

const cardsSort = (cols: number) => {
  const cardsCols = [];

  categories.value.forEach((card, index) => {
    const remains = index % cols;
    if(!cardsCols[remains]){
      cardsCols[remains] = [];
    }
    cardsCols[remains].push(card);
  });
  return cardsCols;
}

const onResize = (e) => {
  changeSizeWindow();
}

// Поучаем ширину окна браузера
const changeSizeWindow = () => {
  widthWindow.value = window.innerWidth;
}

onMounted(async () => {
  changeSizeWindow();
  window.addEventListener('resize', onResize);
});

onUnmounted(() => {
  window.removeEventListener('resize', onResize);
});
</script>

<template>
  <div class="categories-page">
    <div class="flex mt-2 justify-end">
      <router-link
        class="p-2 link-secondary"
        :to="{ name: 'admin.category.create' }"
      >
        Добавить
      </router-link>
    </div>
    <div class="categories-page cards-container mt-3">
      <template v-if="categories.length">
        <div
          class="column-card"
          v-for="categoriesList in categoriesColumnsList"
        >
          <CategoryItem
            v-for="category in categoriesList"
            :key="category.id"
            :id="category.id"
            :title="category.title"
            :description="category.description"
            :url="category.url"
          />
        </div>
      </template>
    </div>
  </div>
</template>

<style scoped>
.cards-container{
  /*column-count: 2;*/
  display: flex;
  flex-direction: row;
  gap: 10px;
}

.column-card{
  flex: 1;
}

@media (max-width: 1140px) {
  .cards-container{
    /*column-count: 1;*/
  }
}
</style>
