var divs = document.querySelectorAll("#account ul>*");

for(let i = 0; i < divs.length; i++){
    divs[i].addEventListener("click", function(){
        // console.log(divs[i]);
        divs[i].classList.add("profile_options_selected");
        for(let n = 0; n < divs.length; n++){
            if(n != i){
                divs[n].classList.remove("profile_options_selected");
            }
        }
    });
}

var contentDiv = document.querySelector("#account_content");

document.querySelector("#account_overview").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>Account Overview</h1>';
});

document.querySelector("#edit_profile").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>Edit Profile</h1>';
});

document.querySelector("#my_posts").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>My Posts</h1>';
});

document.querySelector("#my_comments").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>My Comments</h1>';
});

document.querySelector("#my_saved_posts").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>My Saved Posts</h1>';
});

document.querySelector("#logout").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>Logout</h1>';
});