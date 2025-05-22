import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    base: '/',
    // server: {
    //     https: true,
    // },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/reset.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ]
});
