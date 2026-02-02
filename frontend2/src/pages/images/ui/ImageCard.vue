<script setup lang="ts">
import type { Image } from '@/pages/images/model';

const props = defineProps<Image>();

</script>

<template>
  <div class="w-full flex flex-col gap-2 rounded-lg shadow-lg overflow-hidden bg-white mb-2">
    <img
      class="w-full h-auto object-cover"
      :src="props.path_preview_full"
      :alt="props.title"
    />
  </div>
</template>

<style scoped>

</style>
