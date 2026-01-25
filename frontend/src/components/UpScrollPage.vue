<template>
  <div class="up-scroll-page d-flex flex-column gap-2 position-fixed">

    <div @click="scrollOneScreenUp" class="up-scroll-btn cursor-pointer">
      <i class="bi bi-caret-up"></i>
    </div>

    <div @click="scrollOneScreenDown" class="up-scroll-btn cursor-pointer">
      <i class="bi bi-caret-down"></i>
    </div>

    <div @click="upScroll" class="up-scroll-btn cursor-pointer">
      <i class="bi bi-arrow-up-circle"></i>
    </div>

    <div @click="downScroll" class="up-scroll-btn cursor-pointer">
      <i class="bi bi-arrow-down-circle"></i>
    </div>
  </div>
</template>

<script setup>

import { onMounted, onUnmounted } from 'vue';

onMounted(() => {
  document.body.addEventListener('keyup', scrollOneScreen);
});

onUnmounted(() => {
  document.body.removeEventListener('keyup', scrollOneScreen);
});

const upScroll = () => {
  window.scrollTo(0, 0);
}

const downScroll = () => {
  const h = document.body.scrollHeight;
  window.scrollTo(0, h);
}

const scrollOneScreenUp = (e) => {
  const h = window.outerHeight;
  const scrollPos = window.scrollY - (h - 180);

  window.scrollTo({
    top: scrollPos,
    left: 0,
  });

}

const scrollOneScreenDown = (e) => {
  const h = window.outerHeight;
  const scrollPos = window.scrollY + (h - 180);

  window.scrollTo({
    top: scrollPos,
    left: 0,
  });
}

const scrollOneScreen = (e) => {
  e.stopPropagation();
  e.preventDefault();
  if(e.code === 'ArrowDown') {
    scrollOneScreenDown();
  }

  if(e.code === 'ArrowUp') {
    scrollOneScreenUp();
  }
}

</script>

<style scoped>
.up-scroll-page{
  bottom: 20px;
  right: 20px;
  font-size: 32px;
  background-color: #fff;
  border-radius: 6px;
  box-shadow: 0 0 6px 1px #00000070;
  padding: 6px;
}

.up-scroll-btn{
  width: 32px;
  height: 32px;
  padding: 0;
  margin: 0;
  line-height: 0;
  border-radius: 50%;
  box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
}
</style>