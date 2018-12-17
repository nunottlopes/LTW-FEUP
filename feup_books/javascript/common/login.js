function loginHandler(form, event) {
    event.preventDefault();

    const errordiv = form.nextElementSibling;

    const username = form.querySelector('[name=username]').value;
    const password = form.querySelector('[name=password]').value;

    api.login(username, password).then(response => response.json()).then(function(json) {
        if (json.status === 202) {
            return window.location.reload(true);
        }
        if (json.status === 400) {
            errordiv.textContent = json.message;
        }
    });

    return false;
}
