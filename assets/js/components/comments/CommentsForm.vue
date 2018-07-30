<template>
    <div class="newComment mb-3">
        <div class="alert alert-success" v-if="saved">
            <strong>Success!</strong> Your comment has been added successfully.
        </div>

        <form method="post" @submit.prevent="onSubmit" :class="{'was-validated': errors.length > 0}">
            <div class="form-group">
                <label class="control-label" for="author">Your name:</label>
                <input
                        type="text"
                        name="author"
                        id="author"
                        :class="{'is-invalid': errors.author, 'form-control': true}"
                        v-model="comment.author"
                >
                <div v-if="errors.author" class="invalid-feedback">{{ errors.author }}</div>
            </div>

            <div class="form-group">
                <label class="control-label" for="content">Comment content:</label>
                <textarea
                        name="content"
                        id="content"
                        :class="{'is-invalid': errors.content, 'form-control': true}"
                        v-model="comment.content"
                ></textarea>
                <div v-if="errors.content" class="invalid-feedback">{{ errors.content }}</div>
            </div>

            <button type="submit" class="btn btn-success">Send</button>
        </form>
    </div>
</template>

<script>
    import {CommentResource} from '../../resources/CommentResource';

    export default {
        data() {
            return {
                errors: {},
                saved: false,
                comment: {
                    author: '',
                    content: '',
                },
                data: new FormData(),
                commentResource: new CommentResource()
            };
        },

        props: {
            subject: Number,
            category: {
                type: Boolean,
                default: false
            }
        },

        created() {
            if(this.category) {
                this.comment.category_id = this.subject;
            } else {
                this.comment.post_id = this.subject;
            }
        },

        methods: {
            onSubmit() {
                this.saved = false;
                this.setFormData();
                this.commentResource.create(this.data)
                    .then(
                        ({data}) => {
                            this.setSuccessMessage();
                            this.$emit('add-comment', data);
                            this.reset();
                        }
                    )
                    .catch(({response}) => {
                        this.setErrors(response);
                    });
            },

            setFormData() {
                this.data = this.objectToFormData(this.comment);

            },

            setErrors(response) {
                this.errors = response.data;
            },

            setSuccessMessage() {
                this.reset();
                this.saved = true;
            },

            reset() {
                this.comment = {
                    author: '',
                    content: ''
                };
                this.errors = [];
            }
        }
    }
</script>