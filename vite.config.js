import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default ({ mode }) => {
    // Carrega as variáveis de ambiente correspondentes ao modo atual
    const env = loadEnv(mode, process.cwd());

    return defineConfig({
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            vue()
        ],
        define: {
            'import.meta.env.VITE_APP_BASE_URL': JSON.stringify(env.VITE_APP_BASE_URL || 'http://127.0.0.1:8000/api')
        }
    });
};
