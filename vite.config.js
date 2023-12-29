import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import glob from "glob";

export default defineConfig({
    plugins: [
        laravel({
            publicDirectory: '../1c8.alfastok.by/build',
            input: [...glob.sync('resources/scss/*.scss'), ...glob.sync('resources/js/*.js')],
            refresh: true,
        }),
    ],
    build: {
        outDir: '../1c8.alfastok.by/build',
        minify: false
    }
});


// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import glob from "glob";
//
// export default defineConfig({
//     plugins: [
//         laravel({
//             publicDirectory: '../1c8.alfastok.by/build',
//             input: [...glob.sync('resources/scss/*.scss'), ...glob.sync('resources/js/*.js')],
//             refresh: true,
//         }),
//     ],
//     build: {
//         outDir: '../1c8.alfastok.by/build'
//     }
// });

