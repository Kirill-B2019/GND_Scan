import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                nexus: {
                    bg: '#0a0e17',
                    surface: '#111827',
                    card: '#1e293b',
                    border: '#334155',
                    muted: '#64748b',
                    teal: '#14b8a6',
                    'teal-dim': '#0d9488',
                    amber: '#f59e0b',
                    navy: '#1e3a5f',
                },
            },
        },
    },

    plugins: [forms],
};
