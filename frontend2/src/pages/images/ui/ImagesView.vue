<script setup lang="ts">

import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { getImagesList } from '@/pages/images/api';
import ImageCard from '@/pages/images/ui/ImageCard.vue';

import type { Ref } from 'vue';
import type { Image } from '@/pages/images/model';

const route = useRoute();
const images: Ref<Array<Image>> = ref([]);

const loadImages  = async (): Promise<void> => {
  const categoryId = route.params?.id ? parseInt(route.params.id as string) : 0;
  images.value = await getImagesList(categoryId);
}

onMounted(() => {
  loadImages();
});

</script>

<template>
  <div class="flex flex-row">
    <!-- Grid Masonry layout -->
    <div class="list flex-1">
      <template v-for="image in images" :key="image.id">
        <ImageCard
          v-bind="image"
        />
      </template>
    </div>

    <div class="w-64 ml-4">
      <div class="sticky top-2">
        Filter
      </div>
    </div>
  </div>
</template>

<style scoped>
.list{
  columns: 220px;
  column-gap: 0.5rem;
}
</style>
