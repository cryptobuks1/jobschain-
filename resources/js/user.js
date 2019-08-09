
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';
import VueTimeago from 'vue-timeago'
Vue.use(VueInternationalization); 
Vue.component( 'home', require('./components/user/home.vue') );
import loading from './loading.js';
import base from './base.js'; 
import vmodal from 'vue-js-modal';
import SmartTable from 'vuejs-smart-table';
import VueClipboard from 'vue-clipboard2'
Vue.use(VueClipboard)
Vue.use(SmartTable);
Vue.use(vmodal, { dialog: true })
Vue.directive( 'loading', loading );
Vue.mixin(base);
let language = document.head.querySelector('meta[name="lang"]');
var lang ="en"
if(language){
	lang = language.content;
}
var i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});
Vue.use(VueTimeago, {
  name: 'Timeago', // Component name, `Timeago` by default
  locale: lang, 
  locales: {
    'zh-CN': require('date-fns/locale/zh_cn'),
    'ja': require('date-fns/locale/ja'),
  }
})
const app = new Vue({
    el: '#app',
	i18n
});
window.vm = app;
//require('./init');
