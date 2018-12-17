function registerHandler(form, event) {
    event.preventDefault();

    const errordiv = form.nextElementSibling;

    const username = form.querySelector('[name=username]').value;
    const email = form.querySelector('[name=email]').value;
    const password = form.querySelector('[name=password]').value;
    const password2 = form.querySelector('[name=password2]').value;

    if (password !== password2) {
        errordiv.textContent = "Passwords do not match";
        return false;
    }

    api.user.post({
        username: username,
        email: email,
        password: password
    }, [201, 400]).then(response => response.json()).then(function(json) {
        if (json.status === 201) {
            api.login(username, password).then(function(response) {
                window.location.reload(true);
            });
        }
        if (json.status === 400) {
            errordiv.textContent = json.message;
        }
    });

    return false;
}