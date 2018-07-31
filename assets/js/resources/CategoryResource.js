import {Resource} from "./Resource";

export class CategoryResource extends Resource{
    list() {
        return super.get('api/categories/');
    }

    get(id) {
        return super.get('api/categories/'+id);
    }

    create(data, config = {}) {
        return super.create('api/categories', data, config);
    }

    update(id, data, config = {}) {
        return super.create('api/categories/' + id, data, config);
    }

    delete(id) {
        return super.delete('api/categories/'+id);
    }
}