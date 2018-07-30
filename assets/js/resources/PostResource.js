import {Resource} from "./Resource";

export class PostResource extends Resource{
    create(data) {
        return super.create('api/comments', data);
    }

    delete(id) {
        return super.delete('api/comments/'+id);
    }
}