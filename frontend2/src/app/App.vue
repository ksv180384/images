<script setup lang="ts">

import { onMounted } from 'vue';
import { http } from '@/shared/api';

onMounted(async () => {
  if (!http.isCsrfInitialized()) {
    try {
      await http.getCsrfToken();
    } catch (err) {
      console.error('Failed to get CSRF token:', err);
    }
  }
});
</script>

<template>
  <RouterView />
</template>

<style scoped></style>
