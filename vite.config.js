import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const appUrl = env.APP_URL;

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            hmr: appUrl && appUrl.includes('ngrok') ? {
                host: new URL(appUrl).hostname,
                clientPort: 443,
            } : undefined,
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        },
    };
});
