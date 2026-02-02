import { createRouter, createWebHistory } from 'vue-router';

import { AuthLayout, DefaultLayout } from '@/app/layouts';
import { AUTH_SECTION_ROUTE } from '@/pages/auth';
import { HOME_ROUTE } from '@/pages/home';
import { CATEGORIES_ROUTE } from '@/pages/categories';
import { IMAGES_ROUTE } from '@/pages/images';
import { useUserStore } from '@/entities/user';

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
  if (to.meta.requiresAuth) {
    // const isAuthenticated = await userStore.checkAuth();
    // if (!isAuthenticated) {
    //   return { name: 'login' }
    // }
  }


})

export default router;
