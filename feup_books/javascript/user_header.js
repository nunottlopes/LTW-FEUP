let login_popup = document.querySelector("#login-popup");
let register_popup = document.querySelector("#register-popup");

function openLogIn(){
    login_popup.style.visibility = "visible";
    login_popup.style.opacity = 1;
}

function openSignUp(){
    register_popup.style.visibility = "visible";
    register_popup.style.opacity = 1;
}

function closeLogIn(){
    login_popup.style.visibility = "hidden";
    login_popup.style.opacity = 0;
}

function closeSignUp(){
    register_popup.style.visibility = "hidden";
    register_popup.style.opacity = 0;
}

document.querySelector("#log_in_button").addEventListener('click', () => {
    openLogIn();
});

document.querySelector("#sign_up_button").addEventListener('click', () => {
    openSignUp();
});

login_popup.querySelector("#close_popup").addEventListener('click', () => {
    closeLogIn();
})

register_popup.querySelector("#close_popup").addEventListener('click', () => {
    closeSignUp();
})