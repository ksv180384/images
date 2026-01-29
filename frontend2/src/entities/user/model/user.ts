import { defineStore } from 'pinia';
import { ref } from 'vue';

import type { Ref } from 'vue';

export interface User {
  id: string,
  email: string,
}

interface UserStore {
  isAuth: Ref<boolean>,
  isCheck: Ref<boolean>,
  user: Ref<User | null>,
  setUser: (userData: User | null) => void,
  getUser: () => User | null
  setAuthUser: (value: boolean) => void
  setCheck: (value: boolean) => void
  reset: () => void
}

export const useUserStore = defineStore('userUserStore', (): UserStore => {

  const isAuth: UserStore['isAuth'] = ref(false);
  const isCheck: UserStore['isCheck'] = ref(false);
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

  const reset: UserStore['reset'] = () => {
    isAuth.value = false;
    user.value = null;
  }

  const setCheck: UserStore['setCheck'] = (value) => {
    isCheck.value = value;
  }

  return {
    isAuth,
    isCheck,
    user,
    setUser,
    getUser,
    setAuthUser,
    reset,
    setCheck
  }
});
