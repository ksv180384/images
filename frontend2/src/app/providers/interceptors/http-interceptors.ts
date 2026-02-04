import { http } from '@/shared/api';

export const setupHttpInterceptors = async () => {

  const axiosInstance = http.instance; // Получаем экземпляр

  axiosInstance.interceptors.request.use(
     function (config) {
      return config;
    },
    function (error) {
      return Promise.reject(error);
    }
  );

  axiosInstance.interceptors.response.use(response => response, async (error) => {

    // const router = useRouter();
    // Логика обновления CSRF
    if (error.response?.status === 419) {

      await http.getCsrfToken();
      return axiosInstance(error.config);
    }
    // console.log('ddd')
    // console.log(error.response?.status)
    // console.log(LOGIN_ROUTE.name)
    // console.log(router)
    // console.log(router.currentRoute.value.name)
    // console.log('-----------')
    //
    // if (error.response?.status === 401) {
    //   userStore.reset();
    //   if (router.currentRoute.value.name !== LOGIN_ROUTE.name) {
    //     console.log('ss')
    //     await router.push({ name: LOGIN_ROUTE.name });
    //   }
    //   console.log('ss 222')
    // }
    // console.log('ss 333')
    return Promise.reject(error);
  });
};
