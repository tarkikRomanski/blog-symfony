<template>
    <div class="categoryList">
        <div class="categoryItem panel panel-default" v-for="category in categories">
            <div class="panel-heading categoryItem__header">
                <h3 class="categoryItem__title">
                    <a :href="category.link">
                        {{ category.name }} <span class="badge badge-secondary">{{ category.postsQuantity }}</span>
                    </a>
                </h3>
                <div class="categoryItem__tools">
                    <a href="#" class="btn btn-danger" @click="destroy(category.id)"><i class="fa fa-trash"></i></a>
                    <a :href="category.editLink" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                </div>
            </div>
            <div class="panel-body categoryItem__description">
                {{ category.description }}
            </div>
            <div class="panel-footer">
                <p>Created: {{ category.created }}</p>
                <p>Updated: {{ category.updated }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                categories: [],
                pageCount: 1,
                endpoint: this.getApiUrl('api/categories?page=')
            };
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page = 1) {
                axios.get(this.endpoint + page)
                    .then(({data}) => {
                        this.categories = data.data;
                        this.pageCount = data.meta.last_page;
                    });
            },

            destroy(id) {
                if(confirm('Are you sure you want to delete this category?')) {
                    axios.delete(this.getApiUrl('api/categories/'+id))
                        .then(response => this.removeCategory(id));
                }
            },

            removeCategory(id) {
                this.categories = _.remove(this.categories, function (category) {
                    return category.id !== id;
                });
            }
        }
    }
</script>