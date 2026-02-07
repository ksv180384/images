<script setup lang="ts">
import type { Image } from '@/entities/image/model';
import { More } from '@element-plus/icons-vue';

const props = defineProps<Image>();

</script>

<template>
  <div class="w-full flex flex-col gap-2 rounded-lg shadow-lg overflow-hidden bg-white mb-2 relative">
    <img
      class="w-full h-auto object-cover"
      :src="path_preview_full"
      :alt="title || ''"
    />
    <img
      v-if="path_face"
      :src="path_face"
      class="absolute rounded-full w-[60px] h-[60px] object-contain top-2 left-2 border-2 border-white"
      :alt="title ? `face ${title}` : 'face'"
    />
    <el-dropdown class="!absolute top-2 right-2 rounded-full shadow-md">
      <el-button :icon="More" circle></el-button>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item>Прикрепить тэг</el-dropdown-item>
          <el-dropdown-item>Показать похожие изображения</el-dropdown-item>
          <el-dropdown-item>задать дату создания фото</el-dropdown-item>
          <el-dropdown-item>Скрыть лицо</el-dropdown-item>
          <el-dropdown-item>Удалить</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
    <div class="absolute right-0 bottom-0 px-2 py-1 text-gray-50 bg-gray-900/60 rounded-lg">
      {{ width }} x {{ height }}
    </div>
  </div>
</template>

<style scoped>

</style>
