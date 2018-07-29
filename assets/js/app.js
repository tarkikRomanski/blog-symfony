const $ = require('jquery');

require('bootstrap');

import Vue from 'vue';

Vue.mixin({
    methods: {
        getApiUrl(slug) {
            var protocol = location.protocol;
            var slashes = protocol.concat("//");
            var host = slashes.concat(window.location.hostname);
            return host + '/' + slug;
        }
    }
});

import Categories from './components/categories/CategoriesList.vue';

const app = new Vue({
    el: '#app',
    components: {
        Categories
    }
});