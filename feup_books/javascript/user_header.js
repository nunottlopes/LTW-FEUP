// let login_popup = document.querySelector("#login-popup");
// let register_popup = document.querySelector("#register-popup");

// document.querySelector("#log_in_button").addEventListener('click', () => {
//     login_popup.style.visibility = "visible";
//     login_popup.style.opacity = 1;
// });

// document.querySelector("#sign_up_button").addEventListener('click', () => {
//     register_popup.style.visibility = "visible";
//     register_popup.style.opacity = 1;
// });

// login_popup.querySelector("#close_popup").addEventListener('click', () => {
//     login_popup.style.visibility = "hidden";
//     login_popup.style.opacity = 0;
// })

// register_popup.querySelector("#close_popup").addEventListener('click', () => {
//     register_popup.style.visibility = "hidden";
//     register_popup.style.opacity = 0;
// })

let user_dropdown = document.querySelector("#user-dropdown");

document.querySelector("#profile_button").addEventListener('click', () => {
    if(user_dropdown.style.display == "block") {
        user_dropdown.style.display = "none";
    }
    else {
        user_dropdown.style.display = "block";
    }
})

document.querySelector(".createpost_user_dropdown").addEventListener("click", function(){
    window.location.replace("create_post.php");
});

document.querySelector(".viewprofile_user_dropdown").addEventListener("click", function(){
    window.location.replace("profile.php?id=1");
});

// document.querySelector(".settings_user_dropdown").addEventListener("click", function(){
//     window.location.replace("profile.php?id=1");
// });

document.querySelector(".logout_user_dropdown").addEventListener("click", function(){
    api.logout();
    window.location.replace("index.php");
});

window.onclick = function(event) {
    if (!event.target.matches('#profile_button') && !event.target.matches('#client_image') && !event.target.matches('#client_name')) {
        user_dropdown.style.display = "none";
    }
}
