<template>
  <component :is="layout">
    <main class="main-container container">
      <router-view />
    </main>
  </component>
</template>

<script setup lang="ts">
import {computed, nextTick} from 'vue';
import { useRoute } from 'vue-router';

import DefaultLayout from '@/views/layouts/DefaultLayout.vue';
import AuthLayout from '@/views/layouts/AuthLayout.vue';
import {sendAnalytics} from '@/helpers/helper.js';

type LayoutComponent = typeof DefaultLayout | typeof AuthLayout;
interface RouteMeta {
  layout?: LayoutComponent;
}

const route = useRoute();

const layout = computed<LayoutComponent>(() => {
  // Указываем тип для meta
  const meta = route.meta as RouteMeta;

  // Возвращаем layout из meta или DefaultLayout по умолчанию
  return meta.layout;
});
</script>
