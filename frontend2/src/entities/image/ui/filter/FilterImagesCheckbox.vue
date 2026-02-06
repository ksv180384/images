<script setup lang="ts">
import { useFilterCheckbox } from './composables/useFilterCheckbox';

const props = defineProps<{
  label: string;
  value: string | number; // id тега
}>();

// Используем композабл
const { positiveChecked, negativeChecked } = useFilterCheckbox(props.value);

</script>

<template>
  <div class="flex gap-2 items-center">
    <el-checkbox
      v-model="positiveChecked"
      class="!me-0 !h-[30px] positive-checkbox"
      title="Положительно"
      size="large"
    />

    <el-checkbox
      type="checkbox"
      v-model="negativeChecked"
      class="!h-[30px] negative-checkbox"
      title="Отрицательно"
      size="large"
    />

    <span class="">{{ label }}</span>
  </div>
</template>

<style scoped>
.positive-checkbox :deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
  background-color: #67c23a;
  border-color: #67c23a;
}

.positive-checkbox :deep(.el-checkbox__input.is-checked + .el-checkbox__label) {
  color: #67c23a;
}

.negative-checkbox :deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
  background-color: #f56c6c;
  border-color: #f56c6c;
}

.negative-checkbox :deep(.el-checkbox__input.is-checked + .el-checkbox__label) {
  color: #f56c6c;
}
</style>
