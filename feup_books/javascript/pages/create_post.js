let dropdown_options = document.querySelector("#dropdown_options");


// TODO: get logged in user channels
let channels = ["channel 1", "channel 2", "channel 3", "channel 4"];
channels.forEach(channel => {
    let div = document.createElement('div');
    div.textContent = channel.toUpperCase();
    dropdown_options.appendChild(div);
})

document.querySelector('#new_post_post input[type="submit"]')
.addEventListener('click', event => {
    event.preventDefault();
    let title = document.querySelector('#new_post_post input[name="post_title"]').value;
    let content = document.querySelector('#new_post_post textarea').value;
    console.log(title, content);
});

document.querySelector('#new_post_image input[type="submit"]')
.addEventListener('click', event => {
    event.preventDefault();
    let title = document.querySelector('#new_post_image input[name="post_title"]').value;
    let img = document.querySelector('#new_post_image input[name="post_image"]').value;
    console.log(title, img);
});

document.querySelector('#new_post_title input[type="submit"]')
.addEventListener('click', event => {
    event.preventDefault();
    let title = document.querySelector('#new_post_title input[name="post_title"]').value;
    console.log(title);
});