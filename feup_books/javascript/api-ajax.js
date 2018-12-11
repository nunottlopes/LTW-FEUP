// STILL DEBUGGING, fetch is messy
var DEBUG_API = true;

var api = {
    "settings": {
        credentials: "omit",
        redirect: "follow",
        expect: [200, 201, 300, 401, 403, 404]
    },

    "resource": function(resource, query) {
        const url = new URL('feup_books/api/public/' + resource + '.php', window.location.origin);
        url.search = new URLSearchParams(query);
        return url;
    },

    "fetch": function(resource, query, userInit, userExpect) {
        const url = api.resource(resource, query);
        const expect = new Set(userExpect || this.settings.expect);
        const init = Object.assign({
            method: 'GET',
            mode: 'same-origin',
            credentials: this.settings.credentials,
            redirect: this.settings.redirect
        }, userInit || {});
        
        return window.fetch(url, init).then(function(response) {
            const status = response.status;

            if (DEBUG_API) {
                response.json().then(function(json) {
                    let string = '<pre>' + init.method + ' ' + url + ' ';
                    string += status + ' ' + response.statusText + '</pre>';
                    string += '<pre>' + JSON.stringify(json, null, 4) + '</pre>';

                    document.querySelector('body').innerHTML = string;
                });
            }
        });
    },

    /**
     * Fetch Shortcut methods
     */
    "get": function(resource, query, userExpect) {
        userExpect = userExpect || [200, 404];
        return this.fetch(resource, query, {
            method: 'GET'
        }, userExpect);
    },

    "post": function(resource, query, data, userExpect) {
        userExpect = userExpect || [201, 404];
        return this.fetch(resource, query, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf8'
            },
            body: JSON.stringify(data)
        }, userExpect);
    },

    "put": function(resource, query, data, userExpect) {
        userExpect = userExpect || [200, 201, 404];
        return this.fetch(resource, query, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json; charset=utf8'
            },
            body: JSON.stringify(data)
        }, userExpect);
    },

    "patch": function(resource, query, data, userExpect) {
        userExpect = userExpect || [200, 404];
        return this.fetch(resource, query, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json; charset=utf8'
            },
            body: JSON.stringify(data)
        }, userExpect);
    },

    "delete": function(resource, query, userExpect) {
        userExpect = userExpect || [200, 404];
        return this.fetch(resource, query, {
            method: 'DELETE'
        }, userExpect);
    },

    /**
     * API Resource Shortcut methods
     */
    "user": {
        "get": function(query, expect) {
            return api.get('user', query, expect);
        },
        "put": function(data, expect) {
            return api.put('user', {}, data, expect);
        },
        "delete": function(query, expect) {
            return api.delete('user', query, expect);
        }
    },

    "channel": {
        "get": function(query, expect) {
            return api.get('channel', query, expect);
        },
        "put": function(query, data, expect) {
            return api.put('channel', query, data, expect);
        },
        "delete": function(query, expect) {
            return api.delete('channel', query, expect);
        }
    },

    "comment": {
        "get": function(query, expect) {
            return api.get('comment', query, expect);
        },
        "post": function(query, data, expect) {
            return api.post('comment', query, data, expect);
        },
        "put": function(query, data, expect) {
            return api.put('comment', query, data, expect);
        },
        "delete": function(query, expect) {
            return api.delete('comment', query, expect);
        }
    },

    "story": {
        "get": function(query, expect) {
            return api.get('story', query, expect);
        },
        "post": function(query, data, expect) {
            return api.post('story', query, data, expect);
        },
        "put": function(query, data, expect) {
            return api.put('story', query, data, expect);
        },
        "delete": function(query, expect) {
            return api.delete('story', query, expect);
        }
    },

    "entity": {
        "get": function(query, expect) {
            return api.get('entity', query, expect);
        }
    },

    "tree": {
        "get": function(query, expect) {
            return api.get('tree', query, expect);
        }
    },

    "save": {
        "get": function(query, expect) {
            return api.get('save', query, expect);
        },
        "put": function(query, expect) {
            return api.put('save', query, {}, expect);
        },
        "delete": function(query, expect) {
            return api.delete('save', query, expect);
        }
    },

    "vote": {
        "get": function(query, expect) {
            return api.get('vote', query, expect);
        },
        "put": function(query, data, expect) {
            return api.put('vote', query, data, expect);
        },
        "delete": function(query, expect) {
            return api.delete('vote', query, expect);
        }
    }
};
