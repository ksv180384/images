import { createRouter, createWebHistory } from 'vue-router';

import { AuthLayout, DefaultLayout } from '@/app/layouts';
import { AUTH_SECTION_ROUTE } from '@/pages/auth';
import { LOGIN_ROUTE } from '@/pages/auth/login';
import { HOME_ROUTE } from '@/pages/home';
import { CATEGORIES_ROUTE } from '@/pages/categories';
import { IMAGES_ROUTE } from '@/pages/images';
import { useUserStore } from '@/entities/user';
import { auth } from '@/shared/api';

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
        IMAGES_ROUTE,
      ]
    },
  ]
});

router.beforeEach(async (to, from) => {

  const userStore = useUserStore();

  // Если пользовательакторизован, то не может перейти на страницу авторизации
  if(userStore.isAuth && to.path.startsWith(AUTH_SECTION_ROUTE.path)){
    return { name: HOME_ROUTE.name };
  }

  // Проверяем аутентификацию при необходимости
  if (to.meta?.requiresAuth && !userStore.isAuth) {
    if(!userStore.isCheck){
      const response = await auth.checkAuth();
      if(response?.authenticated){
        userStore.setAuthUser(response.authenticated);
        userStore.setUser(response.user);
      }
      else {
        return { name: LOGIN_ROUTE.name };
      }
    }
  }
});

export default router;
