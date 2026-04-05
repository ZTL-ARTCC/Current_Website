import { createRequire } from 'node:module';
const require = createRequire( import.meta.url );
import { defineConfig } from 'vite';
import ckeditor5 from '@ckeditor/vite-plugin-ckeditor5';
import path from 'path';
import glob from "glob";
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        ckeditor5({
            theme: require.resolve('@ckeditor/ckeditor5-theme-lark')
        }),
        laravel({
            input: [
                ...glob.sync("resources/assets/sass/*.scss"),
                ...glob.sync("resources/assets/js/*.js*"),
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
});
