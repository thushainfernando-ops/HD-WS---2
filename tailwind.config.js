import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#FFF5F2',
                    100: '#FFE8E0',
                    200: '#FFD1C1',
                    300: '#FFB199',
                    400: '#FF8A66',
                    500: '#FF6B35',
                    600: '#F04E1A',
                    700: '#C93D10',
                    800: '#A03310',
                    900: '#7A2A0F',
                },
                darkblue: {
                    500: '#2C3E50',
                    600: '#243342',
                    700: '#1a252f',
                    800: '#151e27',
                    900: '#0d1419',
                }
            },
        },
    },

    plugins: [forms, typography],
};
