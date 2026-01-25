import type { RouteLocationRaw } from 'vue-router';

const HOME_ROUTE_NAME = 'home';

export const HOME_LINK = {
  name: HOME_ROUTE_NAME,
} as const satisfies RouteLocationRaw;
