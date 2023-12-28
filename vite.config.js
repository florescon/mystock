import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import livewire from '@defstudio/vite-livewire-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: false,
        }),
        livewire({  
            refresh: ['resources/css/app.css'], 
        }),
    ],
    // server: {
    //     host: '192.168.1.69',  // Add this to force IPv4 only
    // },
});
