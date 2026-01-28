import { defineStore } from 'pinia';
import { ref } from 'vue';
import { http } from '@/shared/api';

import type { Ref } from 'vue';

interface User {
  id: string,
  email: string,
}

interface UserStore {
  isAuth: Ref<boolean>,
  setUser: (userData: User) => void,
  getUser: () => User | null
  setAuthUser: (value: boolean) => void
  checkAuth: () => void
}

interface CheckAuthData {
  authenticated: boolean,
  user: User
}

export const useUserStore = defineStore('userUserStore', (): UserStore => {

  const isAuth: UserStore['isAuth'] = ref(false);
  const user: Ref<User | null> = ref(null);

  const setUser: UserStore['setUser'] = (userData) => {
    user.value = userData;
  }

  const getUser: UserStore['getUser'] = () => {
    return user.value;
  }

  const setAuthUser: UserStore['setAuthUser'] = (value) => {
    isAuth.value = value;
  }

  const checkAuth: UserStore['checkAuth'] = async () => {
    const { data, status } = await http.fetchGet<CheckAuthData>('check-auth');
    if(data){
      isAuth.value = data.authenticated;
      user.value = data.user;
    }
  }

  return {
    isAuth,
    setUser,
    getUser,
    setAuthUser,
    checkAuth,
  }
});
