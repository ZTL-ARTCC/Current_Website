import { defineConfig } from 'vite';
import path from 'path';
import glob from "glob";
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
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
