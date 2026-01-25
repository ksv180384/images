<template>
  <li class="tag-item">
    <span
      class="select exist"
      :class="{ 'active': isExist }"
      @click="activeExist(tag)"
    >
      &nbsp;
    </span>
    <span
      class="select not-exist"
      :class="{ 'active': isNotExist }"
      @click="activeNotExist(tag)"
    >
      &nbsp;
    </span>
    {{ tag.title }}
  </li>
</template>

<script setup>

import { toRef, ref, defineEmits, computed } from 'vue';

const props = defineProps({
  tagItem: { type: Object, default: {} },
});

const tag = toRef(props, 'tagItem');
const emits = defineEmits(['onChange']);
const active = ref('');

const isExist = computed(() => active.value === 'exist');
const isNotExist = computed(() => active.value === 'not-exist');

const activeExist = () => {
  active.value = active.value === 'exist' ? '' : 'exist';
  emits('onChange', { id: tag.value.id, active: active.value });
}

const activeNotExist = () => {
  active.value = active.value === 'not-exist' ? '' : 'not-exist';
  emits('onChange', { id: tag.value.id, active: active.value });
}

</script>

<style scoped>
li.tag-item{
  display: flex;
  align-items: center;
  padding-left: 4px;
  padding-top: 2px;
  padding-bottom: 2px;
}

li.tag-item .select{
  display: flex;
  justify-content: center;
  align-items: center;
  width: 14px;
  height: 14px;
  cursor: pointer;
  border: 1px solid;
  border-radius: 2px;
  margin-right: 6px;
}

.select.not-exist.active{
  color: #fff;
  background-color: red;
  box-shadow: 0 0 0 2px red;
}

.select.exist.active{
  color: #fff;
  background-color: green;
  box-shadow: 0 0 0 2px green;
}
</style>