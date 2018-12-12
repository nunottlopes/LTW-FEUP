let dropdown_options = document.querySelector("#dropdown_options");
let dropdown_selection = document.querySelector("#dropdown_selection");


// TODO: get logged in user channels
let channels = api.channel.get('all', [200])
.then(response => response.json())
.then(channels => {
    channels.data.forEach(channel => {
        let div = document.createElement('div');
        div.setAttribute('id', channel.channelid);
        div.textContent = channel.channelname.toUpperCase();
        dropdown_options.appendChild(div);
    })
})
.then(() => bindDropdownOptions());

let form_post = document.querySelector('#new_post_post');
form_post.addEventListener('submit', event => {
    event.preventDefault();
    let title = form_post.querySelector('input[name="post_title"]').value;
    let content = form_post.querySelector('textarea').value;
    let channelid = dropdown_selection.getAttribute('selectionid');
    if(title != "" && content != "" && channelid != null) {
        api.story.post({
            channelid: channelid,
            authorid: 1
        }, {
            storyTitle: title,
            storyType: 'text',
            content: content
        }).then(() => window.location.replace('index.php'));
    }
});

let form_img = document.querySelector('#new_post_image');
form_img.addEventListener('submit', event => {
    event.preventDefault();
    let title = form_img.querySelector('input[name="post_title"]').value;
    // let img = form_img.querySelector('input[name="post_image"]').value;
    const img = form_img.querySelector('input[name="post_image"]').files[0];
    let channelid = dropdown_selection.getAttribute('selectionid');
    if(title != "" && img != undefined) {
        //TODO
    }
});

let form_title = document.querySelector('#new_post_title');
form_title.addEventListener('submit', event => {
    event.preventDefault();
    let title = form_title.querySelector('input[name="post_title"]').value;
    let channelid = dropdown_selection.getAttribute('selectionid');
    if(title != "") {
        api.story.post({
            channelid: channelid,
            authorid: 1
        }, {
            storyTitle: title,
            storyType: 'title',
            content: ""
        }).then(() => window.location.replace('index.php'));
    }
});