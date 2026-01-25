<template>
  <form class="create-date-image" @submit.stop.prevent="save">
    <input v-model="date" ref="inputDate" type="date" />

    <div class="d-flex gap-4">
      <BtnStandart
        @click="save"
        style-type="success"
        type="submit"
        :disabled="props.isLoad"
        :is-load="props.isLoad"
        outline
        mini
      >
        Сохранить
      </BtnStandart>
      <BtnStandart
        @click="cansel"
        style-type="danger"
        outline
        mini
      >
        Отмена
      </BtnStandart>
    </div>

  </form>
</template>

<script setup>

import { ref, defineEmits, onMounted } from 'vue';

import BtnStandart from '@/components/form/BtnStandart.vue';

const props = defineProps({
  isLoad: { type: Boolean, required: false, default: false },
  dateCreate: { type: String, required: false, default: '' },
});
const emits = defineEmits(['onSave', 'onCansel']);
const date = ref(props.dateCreate);
const inputDate = ref(null);

onMounted(() => {
  inputDate.value.focus();
});

const save = () => {
  emits('onSave', date.value);
}

const cansel = () => {
  emits('onCansel');
}
</script>

<style scoped>
.create-date-image{
  position: absolute;
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  align-items: center;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background-color: #f9f9f9c9;
  z-index: 2;
}
</style>