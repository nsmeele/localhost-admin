import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            input: {
                js: './resources/js/app.js',
            },
            output: {
                entryFileNames: `js/app.js`,
                /* assetFileNames: `[ext]/app.[ext]`, */
                assetFileNames: ({name}) => {

                    if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')){
                        return 'images/[name].[ext]';
                    }

                    if (/\.css$/.test(name ?? '')) {
                        return 'css/app.[ext]';
                    }

                    return '[name].[ext]';
                },
            },
        }
    },
})