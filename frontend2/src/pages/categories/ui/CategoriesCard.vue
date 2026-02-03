<script setup lang="ts">
import type { Category } from '@/entities/category/model';

const props = defineProps<Category>();

</script>

<template>
<div class="w-[600px] flex gap-2 p-4 rounded-lg shadow-lg border border-gray-200">
  <div class="flex flex-col flex-1 gap-3">
    <div class="text-2xl">
      <RouterLink :to="{ name: 'category.images', params: { id: props.id } }">
        {{ props.title }}
      </RouterLink>
    </div>
    <div>{{ props.desc }}</div>
  </div>
  <img
    class="w-[200px] object-cover object-center rounded"
    :src="props.preview_img"
    :alt="props.title || ''"
  />
</div>
</template>

<style scoped>

</style>
