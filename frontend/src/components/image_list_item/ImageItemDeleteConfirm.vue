<template>
  <div class="delete-confirm-container">
    <div>
      <button
        @click.stop.prevent="deleteItem"
        type="button"
        class="btn btn-outline-danger btn-sm"
      >
        Удалить
      </button>
      <button
        @click.stop.prevent="cansel"
        type="button"
        class="btn btn-outline-success btn-sm"
      >
        Отмена
      </button>
    </div>
  </div>
</template>

<script setup>

import { defineEmits } from 'vue';

const emits = defineEmits('onDelete', 'onCansel');

const deleteItem = () => {
  emits('onDelete');
}

const cansel = () => {
  emits('onCansel');
}
</script>

<style scoped>
.delete-confirm-container{
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background-color: #f9f9f9c9;
  z-index: 3;
}

.delete-confirm-container > div{
  display: flex;
  width: 100%;
  flex-direction: row;
  justify-content: center;
  gap: 14px;
}
</style>