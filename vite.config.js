import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/payment-form.js'
            ],
            refresh: true,
        }),
    ],
    define: {
        'import.meta.env.VITE_PUSHER_APP_KEY': '"4686f348b6ce9c1ef49f"',
        'import.meta.env.VITE_PUSHER_APP_CLUSTER': '"ap1"',
    },
});
