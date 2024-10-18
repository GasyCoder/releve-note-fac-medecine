import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                green: colors.green,
                yellow: colors.yellow,
                red: colors.red,
                gray: colors.gray,
            },
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin')
    ],

    safelist: [
        {
            pattern: /(bg|text)-(green|yellow|red|gray)-(100|200|300|700|800|900)/,
            variants: ['dark'],
        },
    ],
};
