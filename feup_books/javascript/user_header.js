let login_popup = document.querySelector("#login-popup");
let register_popup = document.querySelector("#register-popup");

document.querySelector("#log_in_button").addEventListener('click', () => {
    login_popup.style.visibility = "visible";
    login_popup.style.opacity = 1;
});

document.querySelector("#sign_up_button").addEventListener('click', () => {
    register_popup.style.visibility = "visible";
    register_popup.style.opacity = 1;
});

login_popup.querySelector("#close_popup").addEventListener('click', () => {
    login_popup.style.visibility = "hidden";
    login_popup.style.opacity = 0;
})

register_popup.querySelector("#close_popup").addEventListener('click', () => {
    register_popup.style.visibility = "hidden";
    register_popup.style.opacity = 0;
})