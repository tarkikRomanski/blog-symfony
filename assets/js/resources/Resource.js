import axios from 'axios';

export class Resource {
    getApiUrl(slug) {
        let protocol = location.protocol;
        let slashes = protocol.concat("//");
        let host = slashes.concat(window.location.hostname);
        return host + '/' + slug;
    }

    create(url, data, config = {}) {
        return axios.post(this.getApiUrl(url), data, config);
    }

    delete(url) {
        return axios.delete(this.getApiUrl(url))
    }

    get(url) {
        return axios.get(this.getApiUrl(url));
    }
}