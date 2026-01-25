<template>
    <div class="btn-mini" :title="title">
      <i v-if="props.icon && !props.isLoad" class="bi" :class="iconStyle"></i>
      <div
        v-if="props.isLoad"
        class="spinner-border"
        role="status"
      >
        <span class="visually-hidden">Loading...</span>
      </div>
      <slot></slot>
    </div>
</template>

<script setup>
const props = defineProps({
    title: { type: String, required: false, default: '' },
    icon: { type: String, required: false, default: '' },
    isLoad: { type: Boolean, default: false }
});

const iconStyle = `bi-${props.icon}`

</script>

<style scoped>
.btn-mini{
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    width: 30px;
    height: 30px;
    background-color: #ffffffad;
    color: #0f0f0f;
    border-radius: 6px;
    cursor: pointer;
}
</style>