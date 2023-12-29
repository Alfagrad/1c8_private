import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Alpine from 'alpinejs'
if(window.Alpine === undefined){
    window.Alpine = Alpine
    Alpine.start()
}

import $ from 'jquery';
window.$ = $;
export default $;

// import './vendor/awcodes/filament-tiptap-editor/resources/js/plugin.js'
