<template>
    <div>
        <not-found v-if="notFound"></not-found>
        <div class="postItem" v-if="!notFound">
            <div class="postItem__imageWrap w-100" v-if="post.fileType == 1">
                <img class="postItem__image" :src="post.file" alt="">
            </div>
            <div class="postItem__tools">
                <div class="row">
                    <a :href="post.editLink" class="btn btn-warning col-sm-6 col-12">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" @click="destroy(post.id)" class="btn btn-danger col-sm-6 col-12">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <h1 class="postItem__name">{{ post.name }}</h1>
            <froalaView v-model="post.content"></froalaView>
            <div class="postItem__downloads" v-if="post.fileType != 1 && post.file">
                <a :href="post.file" target="_blank">Download file</a>
            </div>
            <div class="postItem__categories">
                <div v-for="category in post.categories" class="postItem__category mb-2">
                    <a :href="category.link">{{ category.name }}</a>
                </div>
            </div>

            <hr>

            <comments-place
                    :comments="post.comments"
                    :subject="post.id"
            ></comments-place>
        </div>
    </div>
</template>

<script>
    import {PostResource} from '../../resources/PostResource';

    export default {
        data() {
            return {
                post: {},
                notFound: false,
                postResource: new PostResource()
            };
        },

        props: {
            id: {
                type: [String],
                default: false
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch() {
                this.postResource.get(this.id)
                    .then(({data}) => {
                        this.post = data;
                    }).catch(({response}) => {
                        if (response.status == 404) {
                            this.notFound = true;
                        }
                    });
            },

            destroy(id) {
                if(confirm('Are you sure you want to delete this post?')) {
                    this.postResource.delete(this.id)
                        .then(response => {
                            location.href = this.getApiUrl('');
                        });
                }
            },
        }
    }
</script>