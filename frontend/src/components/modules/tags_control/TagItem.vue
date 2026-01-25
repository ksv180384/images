<template>
  <li @click="toggleActivate" class="tag-item list-group-item" :class="{ 'active': isActive }">
    <div class="d-flex justify-content-between align-items-center">
      {{ tag.title }}
      <BtnDelete @click="showConfirm" title="Удалить тег" />
    </div>
    <div v-if="isShowConfirm" class="confirm-block">
      <div @click="deleteItem" class="btn-confirm-block red">Удалить</div>
      <div @click="hideConfirm" class="btn-confirm-block green">Отмена</div>
    </div>
  </li>
</template>

<script setup>
import { ref, toRef, defineEmits } from 'vue';
import api from '@/services/api';

import BtnDelete from '@/components/form/BtnDelete.vue';

const props = defineProps({
  tagItem: { type: Object, required: true }
});
const emits = defineEmits(['onDelete', 'onActivate']);
const tag = toRef(props, 'tagItem');
const isShowConfirm = ref(false);
const isLoadDelete = ref(false);
const isActive = ref(false);

const showConfirm = () => {
  isShowConfirm.value = true;
}

const hideConfirm = () => {
  isShowConfirm.value = false;
}

const deleteItem = async () => {
  if(isLoadDelete.value){
    return true;
  }
  isLoadDelete.value = true;
  try{
    await api.tag.deleteTag(tag.value.id);
    emits('onDelete', tag.value.id);
  } catch (e) {
    console.error(e);
  } finally {
    isLoadDelete.value = false;
  }
}

const toggleActivate = () => {
  isActive.value = !isActive.value;
  emits('onActivate', tag.value.id, isActive.value);
}

</script>

<style scoped>
.tag-item{
  position: relative;
  cursor: pointer;
}

.confirm-block{
  position: absolute;
  display: flex;
  flex-direction: row;
  justify-content: center;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #fff;
  z-index: 1;
}

.btn-confirm-block{
  display: flex;
  justify-content: center;
  align-items: center;
  flex: 1;
  cursor: pointer;
}

.green{
  color: #198754;
}

.green:hover{
  color: #fff;
  background-color: #198754;
}

.red{
  color: #dc3545;
}

.red:hover{
  color: #fff;
  background-color: #dc3545;
}
</style>