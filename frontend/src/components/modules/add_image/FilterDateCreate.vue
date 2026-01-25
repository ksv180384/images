<template>
  <div class="filter-date-create">
    <el-tooltip
      class="box-item"
      effect="light"
      content="Выберите промежеток времени когда были созданы фотографии"
      placement="top-start"
    >
      <el-text large="small">Дата</el-text>
    </el-tooltip>

    <el-date-picker
      v-model="selectDate"
      type="monthrange"
      unlink-panels
      range-separator="-"
      start-placeholder="От"
      end-placeholder="До"
      size="default"
      @change="changeRange"
    />

    <el-checkbox
      v-model="noDate"
      class="mt-1 w-100"
      label="Без даты"
      size="small"
      border
      @change="changeNoDate"
    />

  </div>
</template>

<script setup>
import { ref } from 'vue';

const emits = defineEmits(['onChange', 'onChangeNoDate']);
const selectDate = ref('');
const noDate = ref(false);

const changeRange = (val) => {
  emits('onChange', val);
}

const changeNoDate = (val) => {
  emits('onChangeNoDate', val);
}
</script>

<style>
.filter-date-create{
  text-align: left;
}

.filter-date-create .el-date-editor.el-input__wrapper{
  width: 100%;
}
</style>