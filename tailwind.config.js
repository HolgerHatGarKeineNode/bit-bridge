/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors');

const bitcoin = {
    '50': '#fffdea',
    '100': '#fff8c5',
    '200': '#fff285',
    '300': '#ffe446',
    '400': '#ffd31b',
    '500': '#f2a900',
    '600': '#e28800',
    '700': '#bb5f02',
    '800': '#984908',
    '900': '#7c3c0b',
    '950': '#481e00',
};

export default {
    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",

        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php'
    ],
    theme: {
        extend: {
            colors: {
                amber: bitcoin,
                primary: bitcoin,
                secondary: colors.gray,
                positive: colors.emerald,
                negative: colors.red,
                warning: colors.amber,
                info: colors.blue
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}

