const $ = require('jquery');

require('bootstrap');

window.Vue = require('vue');

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

Vue.component('categories', require('./components/categories/CategoriesList.vue'));

const app = new Vue({
    el: '#app'
});