<template>
    <div class="commentsList">
        <h3>Comments</h3>
        <div class="commentItem mb-3" v-for="comment in comments">
            <div class="card">
                <div class="card-header justify-content-between d-flex">
                    <h5>{{ comment.author }}</h5>
                    <span @click="destroy(comment.id)" class="btn btn-danger"><i class="fa fa-trash"></i></span>
                </div>

                <div class="card-body">
                    <froalaView v-model="comment.content"></froalaView>
                </div>
                <div class="card-footer">
                    <span class="text-muted">comented: {{ comment.created }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
            };
        },

        props: {
            comments: {
                type: Array
            }
        },

        methods: {
            destroy(id) {
                if(confirm('Are you sure you want to delete this comment?')) {
                    axios.delete(this.getApiUrl('api/comments/'+id))
                        .then(response => this.removeComments(id));
                }
            },

            removeComments(id) {
                this.comments.forEach(comment => {
                    if (comment.id === id) {
                        let index = this.comments.indexOf(comment);
                        this.comments.splice(index, 1);
                    }
                });
            }
        }
    }
</script>