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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                display: ['Bebas Neue', 'sans-serif'],
            },
            colors: {
                gold: {
                    DEFAULT: '#E8A020',
                    dark: '#C47D10',
                    light: '#F0B545',
                    muted: 'rgba(232,160,32,0.10)',
                },
                dark: {
                    DEFAULT: '#0E0E0E',
                    100: '#161616',
                    200: '#1E1E1E',
                    300: '#272727',
                    400: '#1A1A1A',
                    500: '#222222',
                },
                cream: '#F5F1E8',
            },
            spacing: {
                'sidebar': '240px',
            },
            borderRadius: {
                'DEFAULT': '10px',
                'sm': '6px',
            },
        },
    },

    plugins: [forms],
};
