import axios from 'axios';

import type { AxiosRequestConfig } from 'axios';

interface HttpConfig {
  baseUrl: string,
  defaultHeaders: Record<string, string>
}

interface HttpResponse<T> {
  data: T | null,
  status: number
}

interface HttpClient {
  fetchData: <T>(config: AxiosRequestConfig) => Promise<T | null>,
  isSuccess: (config: AxiosRequestConfig) => Promise<boolean>,
  fetchFull: <T>(config: AxiosRequestConfig) => Promise<HttpResponse<T>>,
  fetchPost: <T>(path: string, data?: FormData | Record<string, any>, config?: Record<string, string>) => Promise<HttpResponse<T>>,
  fetchGet: <T>(path: string, config?: AxiosRequestConfig) => Promise<HttpResponse<T>>;
  fetchDelete: <T>(path: string, config?: AxiosRequestConfig) => Promise<HttpResponse<T>>;
  fetchDeleteWithBody: <T>(path: string, data?: any, config?: AxiosRequestConfig) => Promise<HttpResponse<T>>;
  setToken: (token: string) => void,
  clearToken: () => void,
  getCsrfToken: () => Promise<void>,
  isCsrfInitialized: () => boolean,
}

const httpClient = ({ baseUrl, defaultHeaders }: HttpConfig): HttpClient => {
  const axiosInstant = axios.create({
    baseURL: baseUrl,
    headers: defaultHeaders,
    withCredentials: true,
  });

  let bearerToken: string | null = null;
  let csrfInitialized = false;

  // Функция для получения CSRF токена
  const getCsrfToken = async (): Promise<void> => {
    try {
      await axiosInstant.get('/sanctum/csrf-cookie');

      csrfInitialized = true;
      console.log('CSRF token получен');
    } catch (error) {
      console.error('Ошибка получения CSRF token:', error);
      throw error;
    }
  };

  const request = async <T>(config: AxiosRequestConfig): Promise<HttpResponse<T>> => {

    const headers = { ...config.headers } as NonNullable<typeof config.headers>;

    // Добавляем Bearer токен если есть
    if(bearerToken !== null){
      headers['Authorization'] = `Bearer ${bearerToken}`;
    }

    try {
      const { data, status } = await axiosInstant.request<T>({
        ...config,
        headers
      });

      return  { data, status };
    } catch (err: unknown) {
      if(axios.isAxiosError(err) && err.response){

        // Если CSRF токен устарел (419 ошибка)
        if (err.response.status === 419 && csrfInitialized) {
          console.log('CSRF токен устарел, обновляем...');
          await getCsrfToken();
          // Повторяем запрос
          return request<T>(config);
        }

        return  {
          data: err.response.data ?? null,
          status: err.response.status ?? 500
        };
      }

      return { data: null, status: 500 };
    }
  }

  // Перехватчик для автоматического получения CSRF токена при 419 ошибке
  axiosInstant.interceptors.response.use(
    response => response,
    async error => {
      if (error.response?.status === 419 && csrfInitialized) {
        console.log('Interceptor: CSRF токен устарел, обновляем...');
        await getCsrfToken();
        // Повторяем оригинальный запрос
        return axiosInstant(error.config);
      }
      return Promise.reject(error);
    }
  );

  const fetchData = async <T>(config: AxiosRequestConfig): Promise<T | null> => {
    const { data } = await request<T>(config);

    return data;
  }

  const isSuccess = async (config: AxiosRequestConfig): Promise<boolean> => {
    const { status } = await request(config);

    return status >= 200 && status < 300;
  }

  const fetchFull = async <T>(config: AxiosRequestConfig): Promise<HttpResponse<T>> => {
    return request<T>(config)
  }

  const fetchGet = async <T>(
    path: string,
    config: AxiosRequestConfig = {}
  ): Promise<HttpResponse<T>> => {
    return request<T>({
      ...config,
      url: path,
      method: 'GET',
      headers: {
        ...defaultHeaders,
        ...config.headers
      }
    });
  }

  const fetchPost = async <T>(
    path: string,
    data?: FormData | Record<string, any>,
    config: AxiosRequestConfig = {}
  ): Promise<HttpResponse<T>> => {

    // Определяем, нужно ли отправлять как FormData
    const isFormData = data instanceof FormData;

    // Подготавливаем заголовки
    const headers = {
      ...config.headers,
      ...(isFormData
        ? {} // FormData сам устанавливает нужный Content-Type с boundary
        : { 'Content-Type': 'application/json' })
    };

    return request<T>({
      ...config,
      url: path,
      method: 'POST',
      headers: headers,
      data: isFormData ? data : JSON.stringify(data)
    });
  }

  const fetchDelete = async <T>(
    path: string,
    config: AxiosRequestConfig = {}
  ): Promise<HttpResponse<T>> => {
    return request<T>({
      ...config,
      url: path,
      method: 'DELETE',
      headers: {
        ...defaultHeaders,
        ...config.headers
      }
    });
  }

  // Для DELETE с телом данных (если нужно)
  const fetchDeleteWithBody = async <T>(
    path: string,
    data?: any,
    config: AxiosRequestConfig = {}
  ): Promise<HttpResponse<T>> => {
    return request<T>({
      ...config,
      url: path,
      method: 'DELETE',
      headers: {
        ...defaultHeaders,
        ...config.headers
      },
      ...(data && { data })
    });
  }

  const setToken = (token: string): void => {
    bearerToken = token;
  }

  const clearToken = (): void => {
    bearerToken = null;
  }

  const isCsrfInitialized = (): boolean => {
    return csrfInitialized;
  };

  return {
    fetchData,
    isSuccess,
    fetchFull,
    fetchGet,
    fetchPost,
    fetchDelete,
    fetchDeleteWithBody,
    setToken,
    clearToken,
    getCsrfToken,
    isCsrfInitialized,
  }
}

const baseUrl = import.meta.env.VITE_API_URL ?? '/';
const defaultHeaders = {
  'Content-Type': 'application/json',
  'Accept': 'application/json',
} as const;

const http = httpClient({ baseUrl: baseUrl, defaultHeaders: defaultHeaders });

export { http };
