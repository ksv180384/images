import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { usePageStore } from '@/stores/page.ts';

import ImageLayout from '@/views/layouts/ImageLayout.vue';
import DefaultLayout from '@/views/layouts/DefaultLayout.vue';
import AuthLayout from '@/views/layouts/AuthLayout.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import(/* webpackChunkName: "about" */ '@/views/IndexPage.vue'),
    meta: {
      layout: DefaultLayout,
    },
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/LoginPage.vue'),
    meta: {
      layout: AuthLayout,
    },
  },
  {
    path: '/registration',
    name: 'registration',
    component: () => import('@/views/auth/RegistrationPage.vue'),
    meta: {
      layout: AuthLayout,
    },
  },
  {
    path: '/forgot-password',
    name: 'forgot_password',
    component: () => import('@/views/auth/ForgotPasswordPage.vue'),
    meta: {
      layout: AuthLayout,
    },
  },
  {
    path: '/reset-password',
    name: 'reset_password',
    component: () => import('@/views/auth/ResetPasswordPage.vue'),
    meta: {
      layout: AuthLayout,
    },
  },
  {
    path: '/admin',
    name: 'admin',
    redirect: { name: 'admin.categories' },
    meta: {
      layout: DefaultLayout,
      auth: true,
    },
    children: [
      {
        path: 'categories',
        name: 'admin.categories',
        component: () => import('@/views/category/Categories.vue'),
      },
      {
        path: 'category',
        name: 'admin.category',
        redirect: { name: 'admin.category.create' },
        children: [
          {
            path: 'create',
            name: 'admin.category.create',
            component: () => import('@/views/category/CreateCategory.vue')
          },
          {
            path: 'edit/:id',
            name: 'admin.category.edit',
            component: () => import('@/views/category/EditCategory.vue')
          },
          {
            path: ':category_id/image/:id',
            name: 'admin.category.image',
            component: () => import('@/views/category/image/ImagePage.vue'),
            meta: {
              layout: ImageLayout,
            },
          }
        ]
      },
      {
        path: 'tags',
        name: 'admin.tags',
        component: () => import('@/views/tag/TagsPage.vue'),
      }
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/errors/NotFoundPage.vue'),
  }
];


const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {

  const authStore = useAuthStore();
  if(to.meta.auth && !authStore.getAuth){
    return next('login');
  }

  const pageStore = usePageStore();
  await pageStore.loadDataPage(to.fullPath, to.query);
  next();

  // Подстановка layout поумолчанию
  const layout = to ? to?.meta?.layout : null;
  to.meta.layout = setDefaultLayout(layout);

});

const setDefaultLayout = (layout) => {
  if(!layout){
    return DefaultLayout
  }

  return layout;
}

export default router;

