import { ref } from 'vue';
import { defineStore } from 'pinia';
import { get } from '@/services/query';
import { usePreloaderPageStore } from '@/stores/preloader_page';

import { IPageStoreState } from '@/interfaces/IStores';

export const usePageStore = defineStore('pageStore', () => {
  const data = ref<IPageStoreState>();

  async function loadDataPage(path: string, params: object): Promise<void>{
    const preloaderPageStore = usePreloaderPageStore();
    preloaderPageStore.setLoading(true);
    const resReq = await get(`${path}`, params);
    preloaderPageStore.setLoading(false);
    data.value = resReq.data;
  }

  function setData(pageData): void {
    data.value = pageData;
  }

  return {
    data,
    loadDataPage,
    setData,
  }
});