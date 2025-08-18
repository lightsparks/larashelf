import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        vue(),
        vuetify({ autoImport: true }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1',
        port: 5174,
        hmr: { host: '127.0.0.1' },
    },
});
