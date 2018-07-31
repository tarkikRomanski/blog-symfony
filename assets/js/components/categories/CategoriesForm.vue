<template>
    <div>
        <not-found v-if="notFound"></not-found>
        <div class="categoryForm" v-if="!notFound">

            <h2>{{ update ? 'Update category' : 'Create category' }}</h2>

            <div class="alert alert-success" v-if="saved">
                <strong>Success!</strong> Your category has been saved successfully.
            </div>
            <form method="post" @submit.prevent="onSubmit" :class="{'was-validated': errors.length > 0}">
                <div class="form-group">
                    <label class="control-label" for="name">Category name:</label>
                    <input
                            type="text"
                            name="name"
                            id="name"
                            :class="{'is-invalid': errors.name, 'form-control': true}"
                            v-model="category.name"
                    >
                    <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="description">Category description:</label>
                    <textarea
                            name="description"
                            id="description"
                            :class="{'is-invalid': errors.description, 'form-control': true}"
                            v-model="category.description"
                    ></textarea>
                    <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
                </div>

                <button type="submit" class="btn btn-success w-100">Submit</button>
            </form>
        </div>
    </div>
</template>

<script>
    import {CategoryResource} from '../../resources/CategoryResource';

    export default {
        data() {
            return {
                errors: [],
                saved: false,
                category: {
                    name: '',
                    description: ''
                },
                data: new FormData(),
                notFound: false,
                categoryResource: new CategoryResource()
            };
        },

        props: {
            update: {
                type: [String, Boolean],
                default: false
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            onSubmit() {
                this.saved = false;
                this.setFormData();
                if (this.update === false) {
                    this.createCategory();
                } else {
                    this.updateCategory();
                }
            },

            fetch() {
                if (this.update !== false) {
                    this.categoryResource.get(this.update)
                        .then(({data}) => this.setCategoryData(data))
                        .catch(({response}) => {
                            if (response.status == 404) {
                                this.notFound = true;
                            }
                        });
                }
            },

            setFormData() {
                this.data = this.objectToFormData(this.category);
            },

            createCategory() {
                this.categoryResource.create(this.data)
                    .then(
                        ({data}) => {
                            this.setSuccessMessage();
                            this.setCategoryData(data);
                        }
                    )
                    .catch(({response}) => this.setErrors(response));
            },

            updateCategory() {
                this.data.append('_method', 'PUT');
                this.categoryResource.update(this.category.id, this.data)
                    .then(({data}) => this.setSuccessMessage())
                    .catch(({response}) => this.setErrors(response));
            },

            setCategoryData(data) {
                this.category.name = data.name;
                this.category.description = data.description;
                this.category.id = data.id;
            },

            setErrors(response) {
                this.errors = response.data;
            },

            setSuccessMessage() {
                this.reset();
                this.saved = true;
            },

            reset() {
                this.errors = [];
                this.data = new FormData();
            }
        }
    }
</script>