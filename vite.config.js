import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
                // Bootstrap resources for frontend
                'resources/js/bootstrap-app.js',
                'resources/css/bootstrap.scss',
                // Custom LESS styles
                'resources/less/app.less',
                // Payment styles
                'resources/css/payment.css'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Silence Bootstrap Sass deprecation warnings
                silenceDeprecations: [
                    'import',
                    'mixed-decls',
                    'color-functions',
                    'global-builtin',
                ],
                api: 'modern-compiler',
                additionalData: `@import "bootstrap/scss/functions";`,
            },
        },
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    // Keep font files in fonts directory
                    if (assetInfo.name.endsWith('.woff2') || assetInfo.name.endsWith('.woff') ||
                        assetInfo.name.endsWith('.ttf') || assetInfo.name.endsWith('.eot')) {
                        return 'fonts/[name][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                }
            }
        }
    }
});
