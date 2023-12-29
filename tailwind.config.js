/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php',
    ],
    safelist: [
        'bg-error-light'
    ],
    theme: {
        extend: {
            colors: {
                'main': '#870020',
                'error': {
                    'light': 'mistyrose',
                    'dark': 'red'
                }
            }

        }
    },
    plugins: [],
}

