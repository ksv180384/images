import { createApp } from 'vue';
import { createPinia } from 'pinia';
import ElementPlus from 'element-plus';
import { setupHttpInterceptors } from '@/app/providers/interceptors/http-interceptors.ts';

import App from '@/app/App.vue';
import router from '@/app/router';
import '@/app/styles/styles.css';
import 'element-plus/dist/index.css';
import ru from 'element-plus/dist/locale/ru.min.mjs';

const app = createApp(App);

app.use(ElementPlus, { locale: ru });
app.use(createPinia());
app.use(router);

setupHttpInterceptors();

export { app };
