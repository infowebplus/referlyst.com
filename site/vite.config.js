import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';
import { readdirSync } from 'fs';

const htmlPages = readdirSync(__dirname)
    .filter((file) => file.endsWith('.html'))
    .reduce((inputs, file) => {
        const name = file.replace(/\.html$/, '');
        inputs[name] = resolve(__dirname, file);

        return inputs;
    }, {});

export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        rollupOptions: {
            input: htmlPages,
        },
    },
});
