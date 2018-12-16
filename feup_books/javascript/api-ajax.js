function apiUnhandledDefaultHandler(response) {
    console.warn("API.FETCH UNHANDLED --- " + response.statusText);
    console.log(response); ++api.handlers.counter;
    api.handlers.unhandled.push(response);

    if (response.status < 500) {
        response.json().then(json => console.log(json));
    } else {
        response.text().then(text => console.log(text));
    }
}

var api = {
    "settings": {
        credentials: "same-origin",
        redirect: "follow",
        expect: [200, 201, 202],
        sendcsrf: true
    },

    "handlers": {
        counter: 0,

        unhandled: [],

        other: apiUnhandledDefaultHandler,
        200:   apiUnhandledDefaultHandler,
        201:   apiUnhandledDefaultHandler,
        202:   apiUnhandledDefaultHandler,
        300:   apiUnhandledDefaultHandler,
        400:   apiUnhandledDefaultHandler,
        401:   apiUnhandledDefaultHandler,
        403:   apiUnhandledDefaultHandler,
        404:   apiUnhandledDefaultHandler,
        405:   apiUnhandledDefaultHandler,
        415:   apiUnhandledDefaultHandler,
        500:   apiUnhandledDefaultHandler,
        503:   apiUnhandledDefaultHandler
    },

    "base": function() {
        const s = location.pathname.split('/');
        return new URL(s.slice(0, s.indexOf('feup_books')).join('/') + '/', location.origin);
    },

    "resource": function(resource, query) {
        const url = new URL('feup_books/api/public/' + resource + '.php', this.base());
        url.search = new URLSearchParams(query);
        return url;
    },

    "ajax": function(resource, query, userInit, userExpect) {
        const url = api.resource(resource, query);
        const expect = new Set(userExpect || api.settings.expect);
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
            return this.test.ajax(resource, query, userInit, userExpect);
        } else {
            return this.ajax(resource, query, userInit, userExpect);
        }
    },

    /**
     * Fetch Shortcut methods
     */
    "get": function(resource, query, userExpect) {
        userExpect = userExpect || [200];
        return this.fetch(resource, query, {
            method: 'GET'
        }, userExpect);
    },

    "post": function(resource, query, data, userExpect) {
        userExpect = userExpect || [201];
        if (typeof data !== 'object') throw "Invalid data in post()";
        if (this.settings.sendcsrf) data.CSRFTOKEN = FEUPBOOK_CSRF_TOKEN;
        return this.fetch(resource, query, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf8'
            },
            body: JSON.stringify(data)
        }, userExpect);
    },

    "put": function(resource, query, data, userExpect) {
        userExpect = userExpect || [200, 201];
        if (typeof data !== 'object') throw "Invalid data in put()";
        if (this.settings.sendcsrf) data.CSRFTOKEN = FEUPBOOK_CSRF_TOKEN;
        return this.fetch(resource, query, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json; charset=utf8'
            },
            body: JSON.stringify(data)
        }, userExpect);
    },

    "patch": function(resource, query, data, userExpect) {
        userExpect = userExpect || [200];
        if (typeof data !== 'object') throw "Invalid data in patch()";
        if (this.settings.sendcsrf) data.CSRFTOKEN = FEUPBOOK_CSRF_TOKEN;
        return this.fetch(resource, query, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json; charset=utf8'
            },
            body: JSON.stringify(data)
        }, userExpect);
    },

    "delete": function(resource, query, userExpect) {
        userExpect = userExpect || [200];
        data = {};
        if (this.settings.sendcsrf) data.CSRFTOKEN = FEUPBOOK_CSRF_TOKEN;
        return this.fetch(resource, query, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json; charset=utf8'
            },
            body: JSON.stringify(data)
        }, userExpect);
    },

    /**
     * API Resource Shortcut methods
     */
    "user": {
        "get": function(query, expect) {
            return api.get('user', query, expect);
        },
        "post": function(data, expect) {
            return api.post('user', {}, data, expect);
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
        "patch": function(query, data, expect) {
            return api.patch('channel', query, data, expect);
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
        },
        "upvote": function(query, expect) {
            return this.put(query, {vote: '+'}, expect);
        },
        "downvote": function(query, expect) {
            return this.put(query, {vote: '-'}, expect);
        }
    },

    "image": {
        "get": function(query, expect) {
            return api.get('image', query, expect);
        },
        "delete": function(query, expect) {
            return api.delete('image', query, expect);
        }
    },

    "login": function login(username, password, expect) {
        return api.put("login", {
            login: 1,
            username: username
        }, {password: password}, expect || [202, 403]);
    },

    "logout": function logout(expect) {
        return api.get("login", "logout", expect || [202]);
    },

    "auth": function auth() {
        return api.get("login", "auth", [200]);
    },

    /**
     * For tests in api/public/test.php
     */
    "test": {
        ajax: function(resource, query, userInit, userExpect) {
            const url = api.resource(resource, query);
            const expect = new Set(userExpect || api.settings.expect);
            const init = Object.assign({
                method: 'GET',
                mode: 'same-origin',
                credentials: api.settings.credentials,
                redirect: api.settings.redirect
            }, userInit || {});

            const closure = {};

            return window.fetch(url, init).then(function(response) {
                closure.response = response;

                const status = response.status;

                if (!api.test.codes[status]) api.test.codes[status] = 0;
                ++api.test.codes[status];

                api.test.responses.push(response);

                return response.json();
            }).then(function(json) {
                const response = closure.response;
                const status = response.status, headers = response.headers;

                api.test.json.push(json);
                let string = '<pre style="font-size:150%">';

                string += init.method + ' ' + url + ' ';
                string += status + ' ' + response.statusText + '\n';

                for (const header of headers.entries()) {
                    const h = header[0], v = header[1];
                    string += h + ': ' + v + '\n';
                }

                string += '\n' + JSON.stringify(json, null, 4);
                string += '</pre>';

                document.querySelector('body').innerHTML = string;

                return json;
            }).catch(function(reason) {
                console.warn("ERROR: ", reason);
            });
        },

        responses: [],
        json: [],
        codes: {},

        clear: function() {
            this.responses.length = 0;
            this.json.length = 0;
            this.codes = {};
        }
    }
};
