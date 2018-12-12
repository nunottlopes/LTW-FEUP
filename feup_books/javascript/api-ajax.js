/**
 * Although a lot of code, this is just a very thin layer
 * around fetch() used for testing and minimum abstraction.
 * Useful for changing expect[] later, as well as the url links
 * to the resources.
 */
var api = {
    "settings": {
        credentials: "same-origin",
        redirect: "follow",
        expect: [200, 201, 202],
        origin: window.location.origin
    },

    "handlers": {
        counter: 0,

        200: function(response) {
            console.warn("API.FETCH UNHANDLED --- 200 OK");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        201: function(response) {
            console.warn("API.FETCH UNHANDLED --- 201 Created");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        202: function(response) {
            console.warn("API.FETCH UNHANDLED --- 202 Accepted");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        300: function(response) {
            console.warn("API.FETCH UNHANDLED --- 300 Multiple Choices");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        400: function(response) {
            console.warn("API.FETCH UNHANDLED --- 400 Bad Request");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        401: function(response) {
            console.warn("API.FETCH UNHANDLED --- 401 Unauthorized");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        403: function(response) {
            console.warn("API.FETCH UNHANDLED --- 403 Forbidden");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        404: function(response) {
            console.warn("API.FETCH UNHANDLED --- 404 Not Found");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        405: function(response) {
            console.warn("API.FETCH UNHANDLED --- 405 Method Not Allowed");
            console.log(response); ++api.handlers.counter;
            response.json().then(json => console.log(json));
        },

        500: function(response) {
            console.warn("API.FETCH UNHANDLED --- 500 Server Error");
            console.log(response); ++api.handlers.counter;
            response.text().then(text => console.log(text));
        },

        503: function(response) {
            console.warn("API.FETCH UNHANDLED --- 503 Service Unavailable");
            console.log(response); ++api.handlers.counter;
            response.text().then(text => console.log(text));
        }
    },

    "resource": function(resource, query) {
        const url = new URL('feup_books/api/public/' + resource + '.php', api.settings.origin);
        url.search = new URLSearchParams(query);
        return url;
    },

    "test": function(resource, query, userInit, userExpect) {
        const url = this.resource(resource, query);
        const expect = new Set(userExpect || this.settings.expect);
        const init = Object.assign({
            method: 'GET',
            mode: 'same-origin',
            credentials: this.settings.credentials,
            redirect: this.settings.redirect
        }, userInit || {});

        return window.fetch(url, init).then(function(response) {
            const status = response.status;
            const headers = response.headers;

            let string = '<pre style="font-size:150%">';

            response.json().then(function(json) {
                string += init.method + ' ' + url + ' ';
                string += status + ' ' + response.statusText + '\n';

                for (const header of headers.entries()) {
                    const h = header[0], v = header[1];
                    string += h + ': ' + v + '\n';
                }

                string += '\n' + JSON.stringify(json, null, 4);
                string += '</pre>';

                document.querySelector('body').innerHTML = string;
            });
        });
    },

    "ajax": function(resource, query, userInit, userExpect) {
        const url = this.resource(resource, query);
        const expect = new Set(userExpect || this.settings.expect);
        const init = Object.assign({
            method: 'GET',
            mode: 'same-origin',
            credentials: this.settings.credentials,
            redirect: this.settings.redirect
        }, userInit || {});

        return window.fetch(url, init).then(function(response) {
            const status = response.status;

            if (expect.has(status)) {
                return response;
            } else {
                if (api.handlers[status]) {
                    api.handlers[status].call(this, response);
                } else {
                    api.handlers.other.call(this, response);
                }

                throw response;
            }
        });
    },

    "fetch": function(resource, query, userInit, userExpect) {
        if (window.APITEST) {
            return this.test(resource, query, userInit, userExpect);
        } else {
            return this.ajax(resource, query, userInit, userExpect);
        }
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
        "patch": function(query, data, expect) {
            return api.patch('story', query, data, expect);
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
    },

    "login": function login(username, password, expect) {
        return api.put("login", {
            login: 1,
            username: username
        }, {password: password}, expect || [202, 401]);
    },

    "logout": function logout(expect) {
        return api.get("login", {
            logout: 1
        }, expect || [202]);
    }
};
