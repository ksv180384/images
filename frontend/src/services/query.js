import axios from 'axios';
import { interceptors } from '@/services/interceptors.js';

const query = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
});

interceptors(query);

export const get = async (path, params = {}, conf = {}) => {
    // Нормализация пути (удаление лишних слэшей)
    const normalizedPath = path.replace(/^\/+|\/+$/g, '');

    // Создание query строки
    const queryString = Object.keys(params).length
        ? `?${new URLSearchParams(params)}`
        : '';

    return query.get(`${normalizedPath}${queryString}`, conf);
};

export const post = async (path, data, conf = {}) => {
    return query.post(path, data, conf);
}

export const put = (path, data, conf = {}) => {
    return query.put(path, data, conf);
}

export const del = (path, data, conf = {}) => {
    return query.delete(path, data, conf);
}

/*
import axios from 'axios';
import { interceptors } from "./interceptors";

const api = axios.create({
    baseURL: '/api/v1',
    // withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
});

interceptors(api);

export const postRequest = async (path, data, config = {}) => {
    await getTokenCSRF();
    return await api.post(path, data, config);
}

export const getRequest = async (path, config = {}) => {
    await getTokenCSRF();
    return await api.get(path, config);
}

export const postRequestPage = async (path, data, config = {}) => {
    return await postRequest(path, data, config);
}

export const getRequestPage = async (path, config = {}) => {
    return await getRequest(path, config);
}

export const requestPage = async (path, params, conf = {}) => {
    path = path.replace(/^[/]+|[/]+$/g, '').trim();

    params = params || {};
    let paramsStr = '';
    if(params){
        paramsStr = '?';
        for (let param in params){
            paramsStr += `${param}=${params[param]}&`;
        }
        paramsStr = paramsStr.slice(0, -1);
    }
    return api.get(`/page/${path}${paramsStr}`, conf);
}

const getTokenCSRF = async () => {
    await api.get('/sanctum/csrf-cookie');
}

// export default api;
*/

