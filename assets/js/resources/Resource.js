import axios from 'axios';

export class Resource {
    getApiUrl(slug) {
        let protocol = location.protocol;
        let slashes = protocol.concat("//");
        let host = slashes.concat(window.location.hostname);
        return host + ':8000/' + slug;
    }

    create(url, data) {
        return axios.post(this.getApiUrl(url), data);
    }

    delete(url) {
        return axios.delete(this.getApiUrl(url))
    }
}