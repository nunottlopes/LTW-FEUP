let login_popup = document.querySelector("#login-popup");
let register_popup = document.querySelector("#register-popup");
//let user_dropdown = document.querySelector("#user-dropdown");


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

// document.querySelector("#profile_button").addEventListener('click', () => {
//     if(user_dropdown.style.display == "block") {
//         user_dropdown.style.display = "none";
//     }
//     else {
//         user_dropdown.style.display = "block";
//     }
// })

// window.onclick = function(event) {
//     if (!event.target.matches('#profile_button')) {
//         user_dropdown.style.display = "none";
//     }
// }
