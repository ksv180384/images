import router from '@/router';
import { useAuthStore } from '@/stores/auth';

export const interceptors = (api) => {
    api.interceptors.request.use(
        function (config) {
            return config;
        },
        function (error) {
            return Promise.reject(error);
        }
    );

    // Обработка ответа сервера для всех заросов
    api.interceptors.response.use(
        async function (response) {
            // При положительном ответе сервера
            return response.data;
        },
        async function (error) {
            // При ответе сервера с ошибкой

            if(error?.response?.status === 401){
                const authStore = useAuthStore();
                authStore.setAuthUser(false);
                window.localStorage.setItem('auth', false);
                router.push('/login');
            }

            if(error?.response?.status === 422 && error?.response?.data?.errors){
                error.response.errors_messages = getErrors(error.response.data.errors);
            }
            return Promise.reject(error);
        }
    );
};

const getErrors = (errors) => {
    const res = {};

    Object.keys(errors).forEach((item) => {
        res[item] = errors[item][0];
    });

    return res;
}
