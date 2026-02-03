import type { RouteRecordRaw, RouteLocationRaw } from 'vue-router';

const IMAGES_ROUTE_NAME = 'category.images';

export const IMAGES_LINK = {
  name: IMAGES_ROUTE_NAME
} as const satisfies RouteLocationRaw;

export const IMAGES_ROUTE = {
  path: '/categories/:id/images',
  name: IMAGES_LINK.name,
  meta: {
    requiresAuth: true,
  },
  component: () => import('@/pages/images/ui')
} as const satisfies RouteRecordRaw
