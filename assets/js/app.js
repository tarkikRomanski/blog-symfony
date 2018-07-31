const $ = require('jquery');

require('bootstrap');

import Vue from 'vue';

Vue.mixin({
    methods: {
        getApiUrl(slug) {
            let protocol = location.protocol;
            let slashes = protocol.concat("//");
            let host = slashes.concat(window.location.hostname);
            return host + '/' + slug;
        },

        objectToFormData(obj, form, namespace) {

            let fd = form || new FormData();
            let formKey;

            for(let property in obj) {
                if(obj.hasOwnProperty(property)) {

                    if(namespace) {
                        formKey = namespace + '[' + property + ']';
                    } else {
                        formKey = property;
                    }

                    if(typeof obj[property] === 'object' && !(obj[property] instanceof File)) {

                        this.objectToFormData(obj[property], fd, property);

                    } else {
                        fd.append(formKey, obj[property]);
                    }

                }
            }

            return fd;

        }
    }
});

/**
 * Froala begin
 */
// Require Froala Editor js file.
require('froala-editor/js/froala_editor.pkgd.min');

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