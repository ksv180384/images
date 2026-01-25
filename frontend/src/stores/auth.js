import { defineStore } from 'pinia';

export const useAuthStore = defineStore('authStore', {
    state: () => ({
        auth_user: window.localStorage.getItem('auth') === 'true',
    }),
    getters: {
        getAuth: (state) => state.auth_user
    },
    actions: {
        setAuthUser(auth){
            auth ? window.localStorage.setItem('auth', auth) : window.localStorage.removeItem('auth');
            this.auth_user = auth;
        }
    }
});