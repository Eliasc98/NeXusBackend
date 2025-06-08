import './bootstrap';

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VueI18n from 'vue-i18n';
import App from './App.vue';
import routes from './routes';
import store from './store';
import i18n from './i18n';
import VueMeta from 'vue-meta';
import VueClipboard from 'vue-clipboard2';

window.Vue = require('vue').default;
const app = new Vue({
    el: '#app',
});