<template>
    <div class="categoryList">
        <div class="categoryItem panel panel-default mb-3" v-for="category in categories">
            <div class="panel-heading categoryItem__header">
                <h3 class="categoryItem__title">
                    <a :href="category.link">
                        {{ category.name }} <span class="badge badge-secondary">{{ category.postsQuantity }}</span>
                    </a>
                </h3>
                <div class="categoryItem__tools">
                    <span class="btn btn-danger" @click="destroy(category.id)"><i class="fa fa-trash"></i></span>
                    <a :href="category.editLink" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                </div>
            </div>
            <div class="panel-body categoryItem__description">
                {{ category.description }}
            </div>
        </div>
    </div>
</template>

<script>
    import {CategoryResource} from '../../resources/CategoryResource';

    export default {
        data() {
            return {
                categories: [],
                categoryResource: new CategoryResource()
            };
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch() {
                this.categoryResource.list()
                    .then(({data}) => {
                        this.categories = data;
                    });
            },

            destroy(id) {
                if(confirm('Are you sure you want to delete this category?')) {
                    this.categoryResource.delete(id)
                        .then(response => this.removeCategory(id));
                }
            },

            removeCategory(id) {
                this.categories.forEach(category => {
                    if (category.id === id) {
                        let index = this.categories.indexOf(category);
                        this.categories.splice(index, 1);
                    }
                });
            }
        }
    }
</script>