import { createRouter, createWebHistory } from 'vue-router';

import { AuthLayout, DefaultLayout } from '@/app/layouts';
import { AUTH_SECTION_ROUTE } from '@/pages/auth';
import { HOME_ROUTE } from '@/pages/home';
import { CATEGORIES_ROUTE } from '@/pages/categories';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      ...AUTH_SECTION_ROUTE,
      component: AuthLayout,
    },
    {
      path: '',
      component: DefaultLayout,
      children: [
        HOME_ROUTE,
        CATEGORIES_ROUTE,
      ]
    },
  ]
})

export default router;
