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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta UDO (2026-10-20): azul primario + naranja + blanco
                brand: {
                    light: '#0056b3',
                    DEFAULT: '#003da5',
                    dark: '#001233',
                    soft: '#eef5fc',
                    accent: '#f7941d',
                    'accent-light': '#ff9f2e',
                },
            },
        },
    },

    plugins: [forms],
};
