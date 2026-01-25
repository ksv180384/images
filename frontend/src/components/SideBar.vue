<template>
  <transition name="show">
    <div class="sidebar" v-show="isShow" tabindex="1">
      <div class="d-flex flex-row w-100 h-100">
        <nav class="d-flex flex-column shadow-lg">
          <div class="sidebar-header text-center py-4">Imagines</div>

          <ul class="flex-grow-1 px-4">
            <template v-for="menu in props.menuList">
              <li v-if="!menu.for_auth || menu.for_auth && props.isAuth">
                <router-link
                  class="link-sidebar"
                  :to="{ name: menu.path_name }"
                  @click.stop="hideSidebar"
                >
                  {{ menu.title }}
                </router-link>
              </li>
            </template>
          </ul>

          <ul class="d-flex flex-row flex-nowrap my-0">
            <template v-if="props.isAuth">
              <li class="flex-grow-1">
                <button @click="logout" class="p-2 btn btn-sm btn-primary w-100 rounded-0">
                  Выход
                </button>
              </li>
            </template>
            <template v-else>
              <li class="flex-grow-1">
                <router-link class="p-2 link-sidebar" :to="{ name: 'login' }">
                  Вход
                </router-link>
              </li>
            </template>
          </ul>

        </nav>
        <div  @click="hideSidebar" class="sidebar-overlay flex-grow-1"></div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { toRef, defineEmits } from 'vue';

const props  = defineProps({
  show: { type: Boolean, default: true },
  menuList: { type: Array, default: [] },
  isAuth: { type: Boolean, default: false },
});

const emits = defineEmits(['onLogout', 'onHideSidebar']);
const isShow = toRef(props, 'show');

const logout = () => {
  emits('onLogout');
}

const hideSidebar = () => {
  emits('onHideSidebar');
}

</script>

<style scoped>

.sidebar{
  position: fixed;
  display: flex;
  flex-direction: column;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  margin: 0 auto;
  z-index: 3;
}

nav{
  width: 100%;
  max-width: 250px;
  height: 100%;
  background-color: #fff;
  /*transition: .5s ease all;*/
}

.sidebar-header{
  text-align: center;
}

nav ul{
  display: flex;
  flex-direction: column;
  padding-left: 0;
}

nav li{
  list-style: none;
}

li .link-sidebar{
  display: inline-block;
  padding: 12px 8px;
  color: #000;
}

.show-enter-from,
.show-leave-to{
  opacity: 0;
  transform: translateX(-250px);
}

.show-enter-active,
.show-leave-active{
  transition: all 200ms ease;
}

@media (min-width: 1140px) {
  nav{
    max-width: 1140px;
  }
}
</style>