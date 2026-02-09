<script setup lang="ts">
import { ref } from 'vue';

import type { Ref } from 'vue';
import type { Image } from '@/entities/image/model';

defineProps<Image>();

const isShowFace: Ref<boolean> = ref(true);

const toggleFace = (): void => {
  isShowFace.value = !isShowFace.value;
};
</script>

<template>
  <div class="w-full flex flex-col gap-2 rounded-lg shadow-lg overflow-hidden bg-white mb-2 relative">
    <img
      class="w-full h-auto object-cover"
      :src="path_preview_full"
      :alt="title || ''"
      :width="width"
      :height="height"
      loading="lazy"
      decoding="async"
    />

<!--    <el-image :src="path_preview_full" lazy></el-image>-->

    <img
      v-if="path_face && isShowFace"
      :src="path_face"
      class="absolute rounded-full w-[60px] h-[60px] object-contain top-2 left-2 border-2 border-white"
      :alt="title ? `face ${title}` : 'face'"
      loading="lazy"
    />
    <details class="image-card-menu">
      <summary class="image-card-menu__trigger" title="Меню">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
      </summary>
      <div class="image-card-menu__dropdown">
        <button type="button" class="image-card-menu__item" @click="toggleFace">
          Скрыть лицо
        </button>
      </div>
    </details>
    <div class="absolute right-0 bottom-0 px-2 py-1 text-gray-50 bg-gray-900/60 rounded-lg">
      {{ width }} x {{ height }}
    </div>
  </div>
</template>

<style scoped>
.image-card-menu {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
}

.image-card-menu__trigger {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  padding: 0;
  border: none;
  border-radius: 50%;
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  list-style: none;
  color: #606266;
}

.image-card-menu__trigger::-webkit-details-marker {
  display: none;
}

.image-card-menu__trigger:hover {
  background: #f5f7fa;
  color: #409eff;
}

.image-card-menu__dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 4px;
  min-width: 120px;
  padding: 4px 0;
  background: white;
  border-radius: 4px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
  z-index: 10;
}

.image-card-menu__item {
  display: block;
  width: 100%;
  padding: 8px 16px;
  border: none;
  background: none;
  font-size: 14px;
  text-align: left;
  cursor: pointer;
  color: #606266;
}

.image-card-menu__item:hover {
  background: #f5f7fa;
  color: #409eff;
}
</style>
