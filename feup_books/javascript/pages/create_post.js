let dropdown_options = document.querySelector("#dropdown_options");
let dropdown_selection = document.querySelector("#dropdown_selection");

var auth;

api.auth().then(response => {return response.json()}).then(json =>{
    if(json.data == null) {window.location.replace("index.php");}
    else{auth = json.data;}
})

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
            authorid: auth.userid
        }, {
            storyTitle: title,
            storyType: 'text',
            content: content
        }).then(() => window.location.replace('index.php'));
    }
    else if(title == "" && content == "" && channelid == null){
        alert("Please fill the form.");
    }
    else if(channelid == null){
        alert("Please select a Channel.");
    }
    else if(title == ""){
        alert("Please add a title.");
    }
    else if(content == ""){
        alert("Please add text.");
    }
});

// let form_img = document.querySelector('#new_post_image');
// form_img.addEventListener('submit', event => {
//     event.preventDefault();
//     let title = form_img.querySelector('input[name="post_title"]').value;
//     // let img = form_img.querySelector('input[name="post_image"]').value;
//     const img = form_img.querySelector('input[name="post_image"]').files[0];
//     let imgid = 1;
//     let channelid = dropdown_selection.getAttribute('selectionid');
//     if(title != "" && img != undefined && channelid != null) {
//         //TODO
//         api.story.post({
//             channelid: channelid,
//             authorid: auth.userid
//         }, {
//             storyTitle: title,
//             storyType: 'image',
//             content: imgid,
//             imageid: imgid
//         }).then(() => window.location.replace('index.php'));
//     }
//     else if(title == "" && img == undefined && channelid == null){
//         alert("Please fill the form.");
//     }
//     else if(channelid == null){
//         alert("Please select a Channel.");
//     }
//     else if(title == ""){
//         alert("Please add a title.");
//     }
//     else if(img == undefined){
//         alert("Please upload an image.");
//     }
// });

let form_img = document.querySelector('#new_post_image');
form_img.addEventListener('submit', event =>{
    event.preventDefault();
    let title = form_img.querySelector('input[name="post_title"]').value;
    let img = form_img.querySelector('input[name="upload-file"]').files[0];
    let channelid = dropdown_selection.getAttribute('selectionid');
       
    if(title == "" && img == undefined && channelid == null) alert("Please fill the form.");
    else if(title == "") alert("Please add a title.");
    else if(img == undefined) alert("Please upload an image");
    else if(channelid == null) alert("Please select a Channel");
    else {
        const formData = new FormData(event.target);
        fetch('/feup_books/api/upload.php', {
            method: 'POST',
            body: formData,
            contentType: false,
            processData: false,
        }).then(r => r.json())
        .then( r =>    
            api.story.post({
                channelid: channelid,
                authorid: auth.userid
            }, {
                storyTitle: title,
                storyType: 'image',
                content: r.info.imagefile,
                imageid: r.id
            }).then(() => window.location.replace('index.php'))
        );
    }
});

let form_title = document.querySelector('#new_post_title');
form_title.addEventListener('submit', event => {
    event.preventDefault();
    let title = form_title.querySelector('input[name="post_title"]').value;
    let channelid = dropdown_selection.getAttribute('selectionid');
    if(title != "" && channelid != null) {
        api.story.post({
            channelid: channelid,
            authorid: auth.userid
        }, {
            storyTitle: title,
            storyType: 'title'
        }).then(() => window.location.replace('index.php'));
    }
    else if(title == "" && channelid == null){
        alert("Please fill the form.");
    }
    else if(channelid == null){
        alert("Please select a Channel.");
    }
    else if(title == ""){
        alert("Please add a title.");
    }
});