
window._ = require('lodash');
window.Popper = require('popper.js').default;
import Vue from 'vue'
//import Form from './services/form'
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('../../node_modules/jquery.stellar/jquery.stellar.js');
require('bootstrap');
/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */



window.Vue = Vue;
//window.Form = Form;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Pusher = require('pusher-js')
import Echo from 'laravel-echo'
let PUSHER_APP_KEY = document.head.querySelector('meta[name="_pusher_key"]');
let PUSHER_CLUSTER = document.head.querySelector('meta[name="_pusher_cluster"]');
if(PUSHER_APP_KEY  && PUSHER_CLUSTER){
	window.Echo = new Echo({
		broadcaster: 'pusher',
		key:PUSHER_APP_KEY.content,
		cluster:PUSHER_CLUSTER.content,
		encrypted: true
	});
}else{
	console.error('PUSHER KEY not found. Please Add meta[name="_pusher_key"] to your Meta Info');
}


