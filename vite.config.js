import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx',
            ],
            refresh: process.env.APP_ENV === 'local', // only enable HMR in local
        }),
        react(),
    ],
    // Dev server config only applies in local development
    server: process.env.APP_ENV === 'local' ? {
        host: '0.0.0.0',
        port: parseInt(process.env.VITE_PORT) || 5174,
        strictPort: true,
        hmr: {
            host: 'localhost',
            clientPort: parseInt(process.env.VITE_PORT) || 5174,
        },
    } : undefined,
    build: {
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            input: 'resources/js/app.jsx',
        },
    },
});

