import { http } from '@/shared/api';
import { useUserStore } from '@/entities/user';
import { useRouter } from 'vue-router';
import { LOGIN_ROUTE } from '@/pages/auth/login';
import { auth } from '@/shared/api';

export const setupHttpInterceptors = () => {
  const router = useRouter();
  const userStore = useUserStore();
  const axiosInstance = http.instance; // Получаем экземпляр

  // Если не было проверки авторизации пользователя, то получаем данные авторизации
  if(!userStore.isCheck){
    auth.checkAuth().then((authData) => {
      userStore.setCheck(true);
      if(authData){
        userStore.setAuthUser(authData.authenticated);
        userStore.setUser(authData.user);
      }
    });

  }

  axiosInstance.interceptors.request.use(
     function (config) {
      return config;
    },
    function (error) {
      return Promise.reject(error);
    }
  );

  axiosInstance.interceptors.response.use(response => response, async (error) => {

    // Логика обновления CSRF
    if (error.response?.status === 419) {

      await http.getCsrfToken();
      return axiosInstance(error.config);
    }

    if (error.response?.status === 401) {
      userStore.reset();
      if (router.currentRoute.value.name !== LOGIN_ROUTE.name) {
        await router.push({ name: LOGIN_ROUTE.name });
      }
    }
      return Promise.reject(error);
  });
};
