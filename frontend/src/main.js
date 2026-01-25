import { createApp } from 'vue';
import { createPinia } from 'pinia';

import App from './App.vue';
import router from './router';

import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';

// import 'assets/main.css';

import ru from 'element-plus/dist/locale/ru.min.mjs';

// import './assets/main.css';

const pinia = createPinia();
const app = createApp(App);


app.use(ElementPlus, { locale: ru });
app.use(pinia);
app.use(router);
app.mount('#app')


