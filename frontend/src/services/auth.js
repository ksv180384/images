import { post } from '@/services/query';

// Авторизация
/*
export const loginRequest = async (loginData) => {
    return await postRequest('auth/login', loginData);
}

export const registrationRequest = async (registrationData) => {
    return await postRequest('auth/register', registrationData);
}

export const logoutRequest = async () => {
    return await postRequest('/auth/logout');
}

export const forgotPasswordRequest = async (data) => {
    return await postRequest('/auth/forgot-password', data);
}
*/

const login = async (params) => {
    return await post('/login', params);
}

const register = async (params) => {
    return await post('/register', params);
}

const logout = async () => {
    return await post('/logout');
}

const forgotPassword = async (params) => {
    return await post('/forgot-password', params);
}

export default {
    login,
    register,
    logout,
    forgotPassword
};