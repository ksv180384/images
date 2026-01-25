<template>
  <el-button
    :type="styleType"
    :native-type="props.type"
    plain
    :class="styleClassBtn"
    :disabled="isDisabled"
    :loading="isShowLoad"
  >
    <slot></slot>
  </el-button>
</template>

<script setup>
import { toRef, computed } from 'vue';

const props = defineProps({
  type: { type: String, default: 'submit' },
  disabled: { type: Boolean, default: false },
  styleType: { type: String, default: 'primary' },
  mini: { type: Boolean, default: false },
  big: { type: Boolean, default: false },
  isLoad: { type: Boolean, default: false }
});

const type = toRef(props, 'button');
const isDisabled = toRef(props, 'disabled');
const isShowLoad = toRef(props, 'isLoad');

const styleClassBtn = computed(() => {
  let s = 'btn'
  s += props.outline ? '-outline' : '';
  s += '-' + props.styleType;
  s += props.mini ? ' btn-sm' : '';
  s += props.big ? ' btn-lg' : '';
  return s;
});

const styleClassSpinner = computed(() => {
  let s = 'text-primary'
  s += '-' + props.style;
  return s;
});
</script>

<style scoped>
.btn .spinner-border{
  width: 16px;
  height: 16px;
}
</style>