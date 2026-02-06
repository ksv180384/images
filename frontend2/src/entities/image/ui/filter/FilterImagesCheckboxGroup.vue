<script setup lang="ts">
import { provide, computed } from 'vue';
import { useFilterCheckboxGroup } from './composables/useFilterCheckboxGroup';
import { FILTER_CHECKBOX_GROUP_SYMBOL } from '@/entities/image/model';

const props = defineProps<{
  modelValue: { id: string | number; value: 'positive' | 'negative' }[];
}>();

const emit = defineEmits<{
  'update:modelValue': [value: { id: string | number; value: 'positive' | 'negative' }[]];
}>();

// Используем композабл
const { groupContext } = useFilterCheckboxGroup(
  computed(() => props.modelValue),
  emit
);

// Предоставляем контекст дочерним компонентам
provide(FILTER_CHECKBOX_GROUP_SYMBOL, groupContext);
</script>

<template>
  <div class="filter-checkbox-group">
    <slot />
  </div>
</template>

<style scoped>

</style>
