<template>
    <div>
        <not-found v-if="notFound"></not-found>
        <div class="row" v-if="!notFound">
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
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                category: {},
                notFound: false
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
                        console.log(data);
                    }).catch(({response}) => {
                        if (response.status == 404) {
                            this.notFound = true;
                        }
                    });
            },
        },
    }
</script>