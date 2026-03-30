import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/website.css', 'resources/js/website.js','resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
