import { fileURLToPath, URL } from 'node:url';

import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers';

//import { quasar, transformAssetUrls } from '@quasar/vite-plugin';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    tailwindcss(),
    vue({
      //template: { transformAssetUrls }
    }),
    AutoImport({
      resolvers: [ElementPlusResolver()],
    }),
    Components({
      resolvers: [ElementPlusResolver()],
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  server: {
    host: true,
    port: 3010,
    proxy: {
      // '/detected-face': {
      //     //target: 'http://imagines_python',
      //     target: 'http://localhost:8001/',
      //     changeOrigin: true,
      //     secure: false,
      //     // logLevel: 'debug'
      // },
      '/api/v1': {
        target: 'http://imagines-nginx',
        changeOrigin: true,
        secure: false,
        // logLevel: 'debug'
      },
    }
  }
})
