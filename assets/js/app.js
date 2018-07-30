const $ = require('jquery');

require('bootstrap');

import Vue from 'vue';

Vue.mixin({
    methods: {
        getApiUrl(slug) {
            var protocol = location.protocol;
            var slashes = protocol.concat("//");
            var host = slashes.concat(window.location.hostname);
            return host + ':8000/' + slug;
        }
    }
});

/**
 * Froala begin
 */
// Require Froala Editor js file.
require('froala-editor/js/froala_editor.pkgd.min');

// Require Froala Editor css files.
require('froala-editor/css/froala_editor.pkgd.min.css');
require('font-awesome/css/font-awesome.css');
require('froala-editor/css/froala_style.min.css');

// Import and use Vue Froala lib.
import VueFroala from 'vue-froala-wysiwyg';
Vue.use(VueFroala);
/**
 * Froala end
 */

import Categories from './components/categories/CategoriesList.vue';
import CategoriesForm from './components/categories/CategoriesForm.vue';
import Category from './components/categories/Category.vue';

import CommentsForm from './components/comments/CommentsForm.vue';
import CommentsList from './components/comments/CommentsList.vue';
import CommentsPlace from './components/comments/CommentsPlace.vue';

import PostsList from './components/posts/PostsList.vue';
import PostsForm from './components/posts/PostsForm.vue';
import Post from './components/posts/Post.vue';

import NotFound from './components/NotFound.vue';

Vue.component(`Categories`, Categories);
Vue.component(`CategoriesForm`, CategoriesForm);
Vue.component(`Category`, Category);

Vue.component(`CommentsForm`, CommentsForm);
Vue.component(`Comments`, CommentsList);
Vue.component(`CommentsPlace`, CommentsPlace);

Vue.component(`Posts`, PostsList);
Vue.component(`PostsForm`, PostsForm);
Vue.component(`Post`, Post);

Vue.component(`NotFound`, NotFound);

const app = new Vue({
    el: '#app'
});