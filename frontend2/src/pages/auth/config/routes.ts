import type { RouteRecordRaw } from 'vue-router';

import { LOGIN_ROUTE, LOGIN_LINK } from '@/pages/auth/login';
import { REGISTER_ROUTE, REGISTER_LINK } from '@/pages/auth/register';

const AUTH_ROUTE_NAME = 'auth';

export const AUTH_SECTION_LINKS = {
  LOGIN: LOGIN_LINK ,
  REGISTER: REGISTER_LINK,
} as const;

export const  AUTH_SECTION_ROUTE = {
  path: '/auth',
  name: AUTH_ROUTE_NAME,
  children:[
    LOGIN_ROUTE,
    REGISTER_ROUTE,
  ],
  redirect: LOGIN_LINK,
} as const satisfies RouteRecordRaw;
