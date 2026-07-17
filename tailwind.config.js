/** @type {import('tailwindcss').Config} */
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eefdf3',
                    100: '#d6f9e0',
                    200: '#b0f1c6',
                    300: '#7ce4a4',
                    400: '#42cf7c',
                    500: '#1cb45d',
                    600: '#11924a',
                    700: '#10733d',
                    800: '#125b34',
                    900: '#104b2d',
                    950: '#032a17',
                },
                accent: {
                    500: '#f97316',
                    600: '#ea580c',
                },
            },
        },
    },
    plugins: [forms, typography],
};
