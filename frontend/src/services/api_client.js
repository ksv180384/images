import { postRequest } from './api';

// Авторизация

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