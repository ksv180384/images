import type { RouteRecordRaw } from 'vue-router';
import { HOME_LINK } from '@/shared/config';

export const HOME_ROUTE = {
  path: '',
  name: HOME_LINK.name,
  component: () => import('@/pages/home/ui'),
} as const satisfies RouteRecordRaw;
