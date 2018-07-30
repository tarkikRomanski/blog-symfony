<template>
    <div class="row">
        <div class="col-md-8 col-12">
            <header>
                <h1>{{ category.name }}</h1>
                <p>{{ category.description }}</p>
            </header>
            <posts  v-if="category.id" :category="category.id"></posts>
        </div>
        <div class="col-md-4 col-12">
            <comments-place
                    v-if="category.id"
                    :comments="category.comments"
                    :subject="category.id"
                    :category="true"
            ></comments-place>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                category: {}
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
                axios.get(this.getApiUrl('api/categories/'+this.id))
                    .then(({data}) => {
                        this.category = data;
                    });
            },
        },
    }
</script>