import {Resource} from "./Resource";

export class CommentResource extends Resource{
    create(data) {
        return super.create('api/comments', data);
    }

    delete(id) {
        return super.delete('api/comments/'+id);
    }
}