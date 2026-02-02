<script setup lang="ts">

import { ref, onMounted } from 'vue';
import { getCategoriesList } from '@/pages/categories/api';
import CategoriesCard from '@/pages/categories/ui/CategoriesCard.vue';

import type { Ref } from 'vue';
import type { Category } from '@/pages/categories/api';

const categories: Ref<Array<Category>> = ref([]);

const loadCategories  = async (): Promise<void> => {
  categories.value = await getCategoriesList();
}

onMounted(() => {
  loadCategories();
});

</script>

<template>
  <div class="flex">
    <div class="flex flex-wrap gap-2 justify-center">
      <template v-for="category in categories" :key="category.id">
        <CategoriesCard
          v-bind="category"
        />
      </template>
    </div>

    <div class="w-[280px]">
      фильтр
    </div>
  </div>
</template>

<style scoped>

</style>
