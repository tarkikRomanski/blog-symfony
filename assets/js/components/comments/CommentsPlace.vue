<template>
    <div class="commentPlace">
        <comments-form
                v-if="subject"
                :subject="subject"
                :category="category"
                v-on:add-comment="onAddComment"
        ></comments-form>

        <comments :comments="comments"></comments>
    </div>
</template>

<script>
    export default {
        props: {
            comments: {
                type: Array
            },
            subject: Number,
            category: {
                type: Boolean,
                default: false
            }
        },

        methods: {
            onAddComment(comment) {
                comment.created = comment.created.date;
                this.comments.unshift(comment);
            }
        }
    }
</script>