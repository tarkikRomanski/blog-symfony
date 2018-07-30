<template>
    <div class="postItem">
        <div class="postItem__imageWrap w-100" v-if="post.fileType == 1">
            <img class="postItem__image" :src="post.file" alt="">
        </div>
        <div class="postItem__metaData">
            <div class="row">
                <div class="col-sm-6 col-12">
                    <strong>Created:</strong> {{ post.created }}
                </div>
                <div class="col-sm-6 col-12">
                    <strong>Updated:</strong> {{ post.updated }}
                </div>
            </div>
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
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                post: {}
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
                axios.get(this.getApiUrl('api/posts/'+this.id))
                    .then(({data}) => {
                        this.post = data.data;
                    });
            },

            destroy(id) {
                if(confirm('Are you sure you want to delete this post?')) {
                    axios.delete(this.getApiUrl('api/posts/'+id))
                        .then(response => {
                            location.href = this.getApiUrl('');
                        });
                }
            },
        }
    }
</script>