import {Resource} from "./Resource";

export class PostResource extends Resource{
    create(data, config = {}) {
        return super.create('api/posts', data, config);
    }

    delete(id) {
        return super.delete('api/posts/'+id);
    }

    get(id) {
        return super.get('api/posts/'+id);
    }

    update(id, data, config = {}) {
        return super.create('api/posts/' + id, data, config);
    }

    list(category) {
        return super.get('api/posts?category=' + category);
    }
}