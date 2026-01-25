import { createApp } from 'vue';
import { createPinia } from 'pinia';
import ElementPlus from 'element-plus';

import App from '@/app/App.vue';
import router from '@/app/router';
import '@/app/styles/styles.css';
import 'element-plus/dist/index.css';

const app = createApp(App);

app.use(ElementPlus);
app.use(createPinia());
app.use(router);

export { app };
