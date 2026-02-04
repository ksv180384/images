<script setup lang="ts">
import { ref } from 'vue';

import type { ImageTag } from '@/entities/image-tag/model';

defineProps<{
  tags: ImageTag[]
}>();

const checkTags = ref([]);
</script>

<template>
<div>
  <el-checkbox-group v-model="checkTags">
    <template v-for="tag in tags" :key="tag.id">
      <el-checkbox :label="tag.title" :value="tag.id" />
    </template>
  </el-checkbox-group>
</div>
</template>

<style scoped>

</style>
