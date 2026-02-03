import type { RouteRecordRaw, RouteLocationRaw } from 'vue-router';

const CATEGORIES_ROUTE_NAME = 'categories';

export const CATEGORIES_LINK = {
  name: CATEGORIES_ROUTE_NAME
} as const satisfies RouteLocationRaw;

export const CATEGORIES_ROUTE = {
  path: '/categories',
  name: CATEGORIES_LINK.name,
  meta: {
    requiresAuth: true,
  },
  component: () => import('@/pages/categories/ui')
} as const satisfies RouteRecordRaw
