<script setup lang="ts">

import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getImagesList, getImageTagsList } from '@/pages/images/api';

import ImageCard from '@/pages/images/ui/ImageCard.vue';
import FilterImages from '@/entities/image/ui/filter/FilterImages.vue';

import type { Ref } from 'vue';
import type { Image } from '@/entities/image/model';
import type { ImageTag } from '@/entities/image-tag/model';
import type { FilterParams } from '@/entities/image/model';

const route = useRoute();
const router = useRouter();

const images: Ref<Array<Image>> = ref([]);
const imageTags: Ref<Array<ImageTag>> = ref([]);

const buildFilterParamsFromRoute = (): FilterParams => {
  const query = route.query;

  const params: FilterParams = {
    tags: null,
    range: null,
    no_date: false,
  };

  if (typeof query.tags === 'string' && query.tags.trim() !== '') {
    params.tags = query.tags.trim();
  }

  const from = typeof query.range_from === 'string' ? query.range_from : null;
  const to = typeof query.range_to === 'string' ? query.range_to : null;

  if (from && to) {
    params.range = [from, to];
  }

  if (query.no_date !== undefined) {
    params.no_date = true;
  }

  return params;
};

const loadImages  = async (params?: FilterParams): Promise<void> => {
  const categoryId = route.params?.id ? parseInt(route.params.id as string) : 0;
  images.value = await getImagesList(categoryId, params);
}

const loadImageTags = async (): Promise<void> => {
  imageTags.value = await getImageTagsList();
}

const filterChange = async (params: FilterParams) => {
  const categoryId = route.params?.id ? parseInt(route.params.id as string) : 0;

  const query: Record<string, string> = {};

  if (params.tags) {
    query.tags = params.tags;
  }

  if (params.range && params.range.length === 2) {
    const [from, to] = params.range as unknown as [string, string];
    if (from) {
      query.range_from = from;
    }
    if (to) {
      query.range_to = to;
    }
  }

  if (params.no_date) {
    query.no_date = '1';
  }

  await router.replace({
    name: route.name as string,
    params: { ...route.params, id: categoryId },
    query,
  });

  await loadImages(params);
}

onMounted(() => {
  const initialParams = buildFilterParamsFromRoute();
  loadImages(initialParams);
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

        <FilterImages :tags="imageTags" @change="filterChange"/>

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
