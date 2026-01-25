<template>
  <div
    @click="toggleActivate"
    class="images-item rounded"
  >
    <div class="img-block rounded" :class="{ 'active': isActive }">
      <img :src="img.path_preview_full">
    </div>
  </div>
</template>

<script setup>
import { computed, toRef, defineEmits } from 'vue';

const emits = defineEmits(['onActivate', 'update:modelValue']);
const props = defineProps({
  image: Object,
  modelValue: { type: Array, default: [] }
});

const img = toRef(props, 'image');

const isActive = computed({
    get() {
      return props.modelValue.indexOf(img.value.id) >= 0;
    },
    set(value) {
      emits('update:modelValue', value)
    }
});

const toggleActivate = () => {
  emits('onActivate', img.value.id, !isActive.value);
}
</script>

<style scoped>
.images-item{
  position: relative;
  display: inline-block;
}

.images-list-item > img{
  height: 320px;
  object-fit: contain;
}

.img-block.active{
  box-shadow: 0 0 0 4px #0d6efd;
}

.img-block{
  cursor: pointer;
  overflow: hidden;
}
</style>