import { defineStore } from 'pinia';
import {ref} from "vue";
import {Ref} from "vue";

export const usePreloaderPageStore = defineStore('preloaderPageStore', () => {
  const loading = ref<boolean>(false);

  function isLoading(): boolean {
    return loading.value;
  }

  function setLoading(val: boolean) {
    loading.value = val;
  }

  return {
    loading,
    isLoading,
    setLoading
  }
});