<template>
    <div class="postForm">
        <div class="alert alert-success" v-if="saved">
            <strong>Success!</strong> Your post has been saved successfully.
        </div>
        <form method="post" @submit.prevent="onSubmit" :class="{'was-validated': errors.length > 0 }">
            <div class="container-fluid">
                <div class="row">
                    <div class="form-group col-12">
                        <label class="control-label" for="name">Post name:</label>
                        <input
                                type="text"
                                name="name"
                                id="name"
                                :class="{'is-invalid': errors.name, 'form-control': true}"
                                v-model="post.name"
                        >
                        <div v-if="errors.name" class="invalid-feedback">{{ errors.name[0] }}</div>
                    </div>

                    <div class="form-group col-12">
                        <label class="control-label" for="content">Post content:</label>
                        <froala :tag="'textarea'" :config="config" v-model="post.content"></froala>
                        <div class="alert alert-danger mt-1 w-100" v-if="errors.content">
                            {{ errors.content[0] }}
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label class="control-label" for="file">Upload File:</label>
                        <input
                                type="file"
                                id="file"
                                ref="file"
                                v-on:change="handleFileUpload"
                                :class="{'is-invalid': errors.file, 'form-control': true}"
                        >
                        <div v-if="errors.file" class="invalid-feedback">{{ errors.file[0] }}</div>
                    </div>
                </div>

                <label>Form categories:</label>
                <div class="row mb-3">
                    <div class="form-check col-lg-4 col-md-6 col-12" v-for="category in categories">
                        <input class="form-check-input" type="checkbox" :id="'category-'+category.id" v-model="checkedCategories" :value="category.id">
                        <label :for="'category-'+category.id" class="form-check-label">
                            {{ category.name }}
                        </label>
                    </div>
                    <div class="alert alert-danger mt-1 w-100" v-if="errors.categories">
                        {{ errors.categories[0] }}
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">Submit</button>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                config: {
                    vueIgnoreAttrs: ['class', 'id']
                },
                errors: [],
                saved: false,
                post: {
                    name: null,
                    content: null
                },
                file: null,
                data: new FormData(),
                categories: {},
                checkedCategories: [],
                ajaxConfig: {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
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
            this.fetchCategories();
        },

        methods: {
            onSubmit() {
                this.setFormData();
                this.saved = false;
                if(this.update === false) {
                    this.createNewPost();
                } else {
                    this.updatePost();
                }
            },

            setFormData() {
                this.data.append('name', this.post.name);
                this.data.append('content', this.post.content);
                if(this.file != null) {
                    this.data.append('file', this.file);
                }
                if(this.checkedCategories.length > 0) {
                    this.data.append('categories', this.checkedCategories.join(','));
                }
            },

            createNewPost() {
                axios.post(
                    this.getApiUrl('api/posts'),
                    this.data,
                    this.ajaxConfig
                ).then(
                    ({data}) => {
                        this.setSuccessMessage();
                        this.setPostData(data);
                    }
                )
                    .catch(({response}) => this.setErrors(response));
            },

            updatePost() {
                this.data.append('_method', 'PUT');
                axios.post(
                    this.getApiUrl('api/posts/' + this.post.id),
                    this.data,
                    this.ajaxConfig
                ).then(({data}) => {
                    this.setSuccessMessage();
                    this.setPostData(data);
                })
                    .catch(({response}) => this.setErrors(response));
            },

            fetch() {
                if (this.update !== false) {
                    axios.get(this.getApiUrl('api/posts/' + this.update))
                        .then(({data}) => {
                            this.setPostData(data.data);
                            this.checkedCategories = [];
                            data.data.categories.forEach(e => {
                                this.checkedCategories.push(e.id);
                            });
                        });
                }
            },

            fetchCategories() {
                axios.get(this.getApiUrl('api/categories/') + '?wholeList=1')
                    .then(({data}) => {
                        this.categories = data.data;
                    });
            },

            setPostData(data) {
                this.post.name = data.name;
                this.post.content = data.content;
                this.post.id = data.id;
                data.categories.forEach(e => {
                    this.checkedCategories.push(e.id);
                });
            },

            handleFileUpload(){
                this.file = this.$refs.file.files[0];
            },

            setErrors(response) {
                this.errors = response.data.errors;
            },

            setSuccessMessage() {
                this.reset();
                this.saved = true;
            },

            reset() {
                this.errors = [];
                this.checkedCategories = [];
                this.data = new FormData();
                this.file = null;
            }
        }
    }
</script>