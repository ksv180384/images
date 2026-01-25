<template>
  <input
    :value="props.modelValue"
    @input="onInput"
    :id="props.id"
    :type="props.type"
    class="form-control"
    :class="{ 'is-invalid': props.error_message }"
    :name="props.name"
    :placeholder="props.placeholder"
    :disabled="props.disabled"
  />
  <label
    v-if="props.label || props.placeholder"
    :for="props.id"
    class="form-label">
    {{ props.label ||props.placeholder }}
  </label>
  <div class="invalid-feedback text-start">
    {{ props.error_message }}
  </div>
</template>

<script setup>
import { defineEmits } from 'vue';

const props = defineProps({
  modelValue: { type: String },
  id: { type: String, default: '' },
  label: { type: String, default: '' },
  type: { type: String, default: 'text' },
  name: { type: String, default: '' },
  placeholder: { type: String, default: '' },
  disabled: { type: Boolean, default: false },
  error_message: { type: String, default: '' },
});

const emits = defineEmits(['update:modelValue']);

const onInput = (e) => {
  emits('update:modelValue', e.target.value);
}
</script>

<style scoped>

</style>