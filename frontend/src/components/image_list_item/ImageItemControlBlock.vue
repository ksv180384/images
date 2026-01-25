<template>
  <div class="image-item-control-block" tabindex="1" @blur="hideShowControl">
    <div>
      <BtnThreeDotsVertical @click.stop.prevent="toggleShowControl"/>
    </div>
    <div v-if="isShowControl" class="btns-list mt-2">
      <BtnMini
        @click.stop.prevent="showTagsControl"
        icon="tags"
        title="Прикрепить теги"
      />
      <BtnMini
        @click.stop.prevent="showSimilar"
        icon="search"
        title="Показать похожие изображения"
      />
      <BtnMini
        @click.stop.prevent="showCreateDateImage"
        icon="calendar3"
        title="Задать дату создания фото"
      />
      <BtnToggleShowFace
        @click.stop.prevent="toggleShowFace"
        :is-show-face="isShowFace"
      />
      <BtnDelete
        @click.stop.prevent="deleteImage"
      />
    </div>
  </div>
</template>

<script setup>
import { defineEmits, ref } from 'vue'

import BtnDelete from '@/components/form/BtnDelete.vue';
import BtnThreeDotsVertical from '@/components/form/BtnThreeDotsVertical.vue';
import BtnToggleShowFace from '@/components/form/BtnToggleShowFace.vue';
import BtnMini from '@/components/form/BtnMini.vue';

const emits = defineEmits([
  'onDeleteImage',
  'onToggleShowFace',
  'onShowTagsControl',
  'onShowCreateDateImage',
  'onShowSimilar',
]);
const isShowControl = ref(false);
const isShowFace = ref(true);

const deleteImage = () => {
  emits('onDeleteImage');
}

const toggleShowControl = () => {
  isShowControl.value = !isShowControl.value;
}

const hideShowControl = () => {
  isShowControl.value = false;
}

const toggleShowFace = () => {
  isShowFace.value = !isShowFace.value;
  emits('onToggleShowFace', isShowFace.value);
}

const showTagsControl = () => {
  emits('onShowTagsControl');
}

const showCreateDateImage = () => {
  emits('onShowCreateDateImage');
}

const showSimilar = () => {
  emits('onShowSimilar');
}

</script>

<style scoped>
.btns-list{
  display: flex;
  flex-direction: column;
  gap: 6px;
}
</style>