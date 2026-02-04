<script setup lang="ts">

import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { getImagesList, getImageTagsList } from '@/pages/images/api';

import ImageCard from '@/pages/images/ui/ImageCard.vue';
import FilterImages from '@/entities/image/ui/filter/FilterImages.vue';

import type { Ref } from 'vue';
import type { Image } from '@/entities/image/model';
import type { ImageTag } from '@/entities/image-tag/model';

const route = useRoute();
const images: Ref<Array<Image>> = ref([]);
const imageTags: Ref<Array<ImageTag>> = ref([]);

const loadImages  = async (): Promise<void> => {
  const categoryId = route.params?.id ? parseInt(route.params.id as string) : 0;
  images.value = await getImagesList(categoryId);
}

const loadImageTags = async (): Promise<void> => {
  imageTags.value = await getImageTagsList();
}

onMounted(() => {
  loadImages();
  loadImageTags();
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

        <FilterImages :tags="imageTags"/>

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
