<template>

  <div class="">

    <SideBar
      :show="isShowSidebar"
      :menu-list="menuStore.menu"
      :is-auth="authStore.getAuth"
      @onLogout="logout"
      @onHideSidebar="toggleSlider"
    />

    <div v-if="!isMobile" class="flex justify-between items-center bg-violet-50 w-full px-30">
      <router-link
        :to="{ name: 'home' }"
        class="header-logo text-dark text-left text-decoration-none"
      >
        Imagines
      </router-link>

      <div class="flex flex-row justify-end items-center">
        <el-menu
          :default-active="defaultActiveMenu"
          :ellipsis="false"
          mode="horizontal"
        >
          <template
            v-for="menuItem in menuStore.menu"
            :key="menuItem.path_name"
          >
            <el-menu-item
              v-if="!menuItem.for_auth || menuItem.for_auth && authStore.getAuth"
              :index="menuItem.path_name"
              class="px-0 bg-violet-50"
            >
              <router-link :to="{ name: menuItem.path_name }" class="px-4">{{ menuItem.title }}</router-link>
            </el-menu-item>
          </template>

          <el-menu-item v-if="!authStore.getAuth" index="login" class="px-0">
            <router-link :to="{ name: 'login' }" class="px-4">Вход</router-link>
          </el-menu-item>

        </el-menu>

        <el-dropdown v-if="authStore.getAuth">
          <span class="el-dropdown-link">
            <el-avatar :size="40" src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png" />
          </span>

          <template #dropdown>
            <el-dropdown-menu>
              <el-dropdown-item>
                Панель администратора
              </el-dropdown-item>
              <el-dropdown-item>
                Настройки
              </el-dropdown-item>
              <el-dropdown-item>
                Профиль
              </el-dropdown-item>
              <el-dropdown-item divided @click="logout">
                Выйти
              </el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>
      </div>
    </div>

    <div
      v-if="isMobile"
      @click="toggleSlider"
      class="flex justify-end"
    >
      <div class="cursor-pointer fs-1 font-bold">
        <i class="bi bi-list"></i>
      </div>
    </div>

  </div>

  <div class="w-full px-30">
    <slot/>
  </div>

  <div v-loading.fullscreen.lock="isLoadingPage"></div>

</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useMenuStore } from '@/stores/menu';
import { useAuthStore } from '@/stores/auth';
import { usePreloaderPageStore } from '@/stores/preloader_page.ts';
import api from '@/services/api.ts';

import SideBar from '@/components/SideBar.vue';

const authStore = useAuthStore();
const menuStore = useMenuStore();
const preloaderPageStore = usePreloaderPageStore();
const router = useRouter();
const route = useRoute();
const loadLogout = ref(false);
const isMobile = ref(false);
const isShowSidebar = ref(false);
const windowWidth = ref(null);
const defaultActiveMenu = ref(route.name);

const isLoadingPage = computed(() => {
  return preloaderPageStore.loading;
});

const toggleSlider = () => {
  isShowSidebar.value = !isShowSidebar.value;
}

const checkScreen = () => {
  windowWidth.value = window.innerWidth;
  if(windowWidth.value <= 750){
    isMobile.value = true;
    return true;
  }
  isMobile.value = false;
  isShowSidebar.value = false;
  return true;
}

const logout = async () => {
  if(loadLogout.value){
    return true;
  }
  loadLogout.value = true;
  try{
    await api.auth.logout();
    authStore.setAuthUser(false);
    router.push('/');
  } catch (e) {
    console.error(e);
  } finally {
    loadLogout.value = false;
  }
}

onMounted(() => {
  window.addEventListener('resize', checkScreen);
  checkScreen();
});

onUnmounted(() => {
  window.removeEventListener('resize', checkScreen);
});
</script>
<style>
.el-menu--horizontal {
  border-bottom: none !important;
}
</style>
