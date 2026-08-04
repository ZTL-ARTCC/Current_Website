import { createRequire } from 'node:module';
const require = createRequire( import.meta.url );
import { defineConfig } from 'vite';
import path from 'path';
import { globSync } from "glob";
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...globSync("resources/assets/sass/*.scss"),
                ...globSync("resources/assets/js/*.js*"),
		...globSync("resources/assets/img/{*,**/*}.{png,svg,jpg,webp,avif}"),
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(import.meta.dirname, 'node_modules/bootstrap'),
        }
    },
});
