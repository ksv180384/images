<script setup lang="ts">
import { reactive, ref, onMounted, nextTick, watch } from 'vue';
import { useRoute } from 'vue-router';

import type { Ref } from 'vue';
import type { FilterParams, TagSelection } from '@/entities/image/model';
import type { ImageTag } from '@/entities/image-tag/model';

import FilterImagesCheckbox from '@/entities/image/ui/filter/FilterImagesCheckbox.vue';
import FilterImagesCheckboxGroup from '@/entities/image/ui/filter/FilterImagesCheckboxGroup.vue';

defineProps<{
  tags: ImageTag[]
}>();

const emits = defineEmits(['change']);

const route = useRoute();

const filterParams = reactive<FilterParams>({
  tags: null,
  range: null,
  no_date: false,
});
const checkTags: Ref<TagSelection[]> = ref([]);

const initFromQuery = (): void => {
  const { tags, range_from, range_to, no_date } = route.query;

  // Теги
  if (typeof tags === 'string' && tags.trim() !== '') {
    filterParams.tags = tags.trim();

    // Используем Map для обработки дубликатов (если тег указан несколько раз, берём последнее значение)
    const tagMap = new Map<string | number, TagSelection>();

    tags.split(',').forEach((item) => {
      const part = item.trim();
      if (!part) return;

      const [idStr, flag] = part.split('-', 2);
      if (!idStr || (flag !== '0' && flag !== '1')) return;

      // Преобразуем id в число, если возможно, иначе оставляем строкой
      const id = /^\d+$/.test(idStr) ? parseInt(idStr, 10) : idStr;

      tagMap.set(id, {
        id,
        value: flag === '1' ? 'positive' : 'negative',
      });
    });

    checkTags.value = Array.from(tagMap.values());
  }

  // Диапазон дат
  if (typeof range_from === 'string' && typeof range_to === 'string') {
    filterParams.range = [range_from, range_to];
  }

  // Без даты
  if (no_date !== undefined) {
    filterParams.no_date = true;
  }
};

const filterTagsChange = (): void => {
  if (!checkTags.value || checkTags.value.length === 0) {
    filterParams.tags = null;
    emits('change', filterParams);
    return;
  }

  const params = checkTags.value
    .map((item) => `${item.id}-${item.value === 'positive' ? 1 : 0}`)
    .join(',');

  filterParams.tags = params || null;
  emits('change', filterParams);
}

const changeRange = () => {
  emits('change', filterParams);
}

const changeNoDate = () => {
  emits('change', filterParams);
}

// Отслеживаем изменения query параметров
watch(
  () => route.query,
  () => {
    initFromQuery();
  },
  { immediate: false }
);

onMounted(async () => {
  await nextTick();
  initFromQuery();
});
</script>

<template>
<div class="flex flex-col h-[calc(100vh-60px)] overflow-auto px-2">
  <div class="flex-1">
    <div class="filter-date-create">
      <el-tooltip
        class="box-item"
        effect="light"
        content="Выберите промежуток времени когда были созданы фотографии"
        placement="top-start"
      >
        <el-text large="small">Дата</el-text>
      </el-tooltip>

      <el-date-picker
        v-model="filterParams.range"
        type="monthrange"
        unlink-panels
        range-separator="-"
        start-placeholder="От"
        end-placeholder="До"
        size="default"
        value-format="YYYY-MM"
        format="MMM YYYY"
        class="max-w-full"
        @change="changeRange"
      />

      <el-checkbox
        v-model="filterParams.no_date"
        class="mt-1 w-full"
        label="Без даты"
        size="small"
        border
        @change="changeNoDate"
      />

    </div>
  </div>
  <div class="h-[calc(90vh-60px)] overflow-auto">
    <FilterImagesCheckboxGroup
      :key="JSON.stringify(checkTags)"
      v-model="checkTags"
      @update:modelValue="filterTagsChange"
    >
      <template v-for="tag in tags" :key="tag.id">
        <FilterImagesCheckbox :label="tag.title" :value="tag.id" :index="tag.id"/>
      </template>
    </FilterImagesCheckboxGroup>
  </div>
</div>
</template>

<style scoped>

</style>
