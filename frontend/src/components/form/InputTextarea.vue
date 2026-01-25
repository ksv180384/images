<template>
  <label
    v-if="props.label"
    :for="props.id"
    class="form-label">
    {{ props.label }}
  </label>
  <textarea
    :value="props.modelValue"
    @input="onInput"
    :rows="textareaRows"
    class="form-control"
    :class="{ 'is-invalid': props.error_message }"
    :id="props.id"
    :placeholder="props.placeholder"
    :disabled="props.disabled"
  ></textarea>
  <div class="invalid-feedback text-start">
    {{ props.error_message }}
  </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';

const props = defineProps({
  modelValue: { type: String },
  id: { type: String, default: '' },
  label: { type: String, default: '' },
  name: { type: String, default: '' },
  placeholder: { type: String, default: '' },
  rows: { type: String, default: '' },
  error_message: { type: String, default: '' },
  disabled: { type: Boolean, default: false },
});

const emits = defineEmits(['update:modelValue']);
const textareaRows = ref(props.rows);

const onInput = (e) => {
  changeTextareaRows(e.target);
  emits('update:modelValue', e.target.value);
}

// Если появился скролл, то увеличиваем строки в поле ввода описания
const changeTextareaRows = (el) => {
  if (el.clientHeight < el.scrollHeight) {
    const lineHeight = el.clientHeight / el.rows;
    const rows = el.scrollHeight / lineHeight;
    textareaRows.value = Math.ceil(rows);
  }
}
</script>

<style scoped>

</style>