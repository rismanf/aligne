import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    base: '/build/', // 👈 penting untuk asset path saat production
    build: {
        outDir: 'public/build', // 👈 hasil build akan masuk ke sini
        emptyOutDir: true,       // hapus isi folder sebelum build ulang
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
