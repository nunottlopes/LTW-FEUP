let main_page_posts = document.querySelector('#main_page_posts');
let noMoreStories = false;
let user;

let settings = {
    sort: document.querySelector("#dropdown_selection").getAttribute("selectionid"),
    limit: 5,
    offset: 0
}

api.auth().then(response => {return response.json()}).then(json =>{
    if(json.data == false)
        user = null;
    else
        user = json.data;
    getContent();
})

function getContent() {
    getStoriesContent();
    getFavouritePostsContent();
}

function getStoriesContent(){
    api.story.get({all: 1, order: settings.sort, limit: settings.limit, offset: settings.offset}, [200])
    .then(response => response.json())
    .then(json => getStories(json.data));
}

function getFavouritePostsContent(){
    if(user != null){
        api.save.get({userid:user.userid, limit:5, stories:""})
        .then(response => response.json())
        .then(json => favouritePosts(json.data)); 
    }
    else{
        let aside_div = document.querySelector('#aside_favorite_post ul');
        aside_div.innerHTML = '<p>Log in to see your favourite posts.</p>';
    }
}

function getStories(data) {
    for(let story of data) {
        /*
        let a1 =
        `<article class="post_preview">
            <header>Posted by ${data[story].authorname} ${timeDifference(data[story].createdat)}</header>
            <a href="post.php?id=${data[story].entityid}">
            <h1>${data[story].storyTitle}</h1>`;
        
        let a2 = "";
        switch(data[story].storyType) {
            case "image":
                a2 = `<img src="images/upload/medium/${data[story].imagefile}" alt="post image">`;
                break;
            case "text":
                a2 = `<p>${data[story].content}</p>`;
                break;
            default:
                break;
        }
    
        let a3 = `</a>
            <footer id=post_button_${data[story].entityid}>
                <button class="post_button" onclick="upvote(${data[story].entityid})"><i class='fas fa-arrow-up'></i> ${data[story].upvotes} Upvotes</button>
                <button class="post_button" onclick="downvote(${data[story].entityid})"><i class='fas fa-arrow-down'></i> ${data[story].downvotes} Downvotes</button>
                <a href="post.php?id=${data[story].entityid}"><button class="post_button"><i class="fa fa-comment"></i> ${data[story].count} Comments</button></a>
                <button class="post_button" id="save${data[story].entityid}" onclick="save(${data[story].entityid})"><i class="fa fa-bookmark"></i> Save</button>
                <button class="post_button" onclick="share(${data[story].entityid})"><i class="fa fa-share-alt"></i> Share</button>
            </footer>
        </article>`;*/

        main_page_posts.appendChild(htmlStoryMainPage(story)); // += a1 + a2 + a3;
    }

    if(user != null){
        for(let story in data){
            updateButtons(user.userid, data[story].entityid);
        }
    }

    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10) && (data.length != 0)) {
        settings.offset += settings.limit;
        getStoriesContent();
    }

    if(data.length == 0)
        noMoreStories = true;
}

function favouritePosts(data){
    let aside_div = document.querySelector('#aside_favorite_post ul');

    if(data.length == 0)
        aside_div.innerHTML = '<p>No favourite posts.</p>';

    for(let post in data){
        let posttitle = data[post].storyTitle;
        let postid= data[post].entityid;
        let a = document.createElement('a');
        a.textContent = posttitle;
        a.setAttribute('href', `post.php?id=${postid}`);

        aside_div.appendChild(a);
    }
}

api.channel.get({all: 1}, [200])
.then(response => response.json())
.then(json => getChannels(json.data));

function getChannels(data) {
    let all_channels = document.querySelector('#aside_channels ul');
    all_channels.innerHTML = "";
    for(let channel in data) {
        let channelname = data[channel].channelname;
        let channelid = data[channel].channelid;
        let a = document.createElement('a');
        a.textContent = channelname;
        a.setAttribute('href', `channel.php?id=${channelid}`);
 
        all_channels.appendChild(a);  
    }
}


document.querySelectorAll("#dropdown_options > *").forEach(element => {
    element.addEventListener('click', () => {
        main_page_posts.innerHTML = "";
        settings.offset = 0;
        settings.sort = element.getAttribute("id");
        getStoriesContent();
    });
})

window.onscroll = () => {
    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10) && (noMoreStories == false)) {
        settings.offset += settings.limit;
        getStoriesContent();
    }
}

function createPostButton(){
    if(user != null)
        window.location.replace("create_post.php");
    else{
        openLogIn();
    }
}

function createChannelButton(){
    if(user != null)
        window.location.replace("create_channel.php");
    else{
        openLogIn();
    }
}

// function openLogIn(){
//     document.querySelector("#login-popup").style.visibility = "visible";
//     document.querySelector("#login-popup").style.opacity = 1;
// }

// function openSignUp(){
//     document.querySelector("#register-popup").style.visibility = "visible";
//     document.querySelector("#register-popup").style.opacity = 1;
// }

var storySettings = {
    bannerwidth: 40,
    bannerheight: 40,
    picturewidth: 30,
    pictureheight: 30
};

function defaultBanner(channelid) {
    const i = (channelid % 5) + 1;
    return `banner${i}.jpeg`;
}

function defaultPicture(userid) {
    const i = (userid % 5) + 1;
    return `user${i}.jpeg`;
}

function imagelink(folder, filename) {
    return `/feup_books/images/upload/${folder}/${filename}`;
}

function htmlStoryMainPage(story) {
    // story
    const entityid = story.entityid;
    const storylink = 'post.php?id=' + entityid;
    
    // channel-info
    const channelid = story.channelid;
    const channelname = story.channelname;
    const bannerfile = story.bannerfile || defaultBanner(story.channelid);
    const channellink = 'channel.php?id=' + channelid;

    const bannersrc = imagelink('thumbnail', bannerfile);

    // score-info
    const vote = story.vote || "";
    const score = vote ? (vote === '+' ? story.score - 1 : story.score + 1) : story.score;
    
    // author-info
    const authorid = story.authorid;
    const authorname = story.authorname;
    const picturefile = story.picturefile || defaultPicture(story.authorid);
    const authorlink = 'profile.php?id=' + authorid;

    const picturesrc = imagelink('thumbnail', picturefile);

    // timestamp
    const createdat = story.createdat;
    const updatedat = story.updatedat;
    const timestamp = timeDifference(createdat);

    // story-post 
    const type = story.storyType;
    const title = story.storyTitle;
    const content = story.content;
    const imagefile = story.imagefile;
    const imagesrc = imagelink('original', imagefile);

    // story-comments
    const count = story.count;

    // story-save
    const save = story.save ? 1 : 0;

    const div = document.createElement('div');

    // Main HTML
    const html = `<article id="story${entityid}" class="story story-mainpage post_preview" data-entityid="${entityid}">
        <div class="channel-info story-channel" data-channelid="${channelid}">
            <a href="${channellink}">
                <img class="varimg channel-banner" src="${bannersrc}" data-bannerfile="${bannerfile}"/>
                <span class="channelname" data-channelname="${channelname}">${channelname}</span>
            </a>
        </div>
        <div class="score-info story-score" data-vote="${vote}" data-score="${score}">
            <i class="fas fa-arrow-up" onclick="upvote(${entityid})"></i>
            <span class="score" data-score="${score}" data-score-up="${score+1}" data-score-down="${score-1}"></span>
            <i class="fas fa-arrow-down" onclick="downvote(${entityid})"></i>
        </div>
        <div class="author-info story-author" data-authorid="${authorid}">
            <a href="${authorlink}">
                <img class="varimg author-picture" src="${picturesrc}" data-picturefile="${picturefile}"/>
                <span>
                    <span class="authorname" data-authorname="${authorname}">${authorname}</span>
                    <span class="timestamp post-timestamp" data-createdat="${createdat}" data-updatedat="${updatedat}">${timestamp}</span>
                </span>
            </a>
        </div>
        <div class="story-post">
            <h2 class="story-title"></h2>
        </div>
        <div class="story-comments">
            <a href="{link story}">
                <button class="post_button">
                    <i class="fa fa-comment"></i>
                    <span class="story-count" data-count="${count}">${count} Comments</span>
                </button>
            </a>
        </div>
        <div class="story-save" data-save="${save}">
            <button class="post_button" onclick="save(${entityid})">
                <i class="fa fa-bookmark"></i>
                <span class="story-save">Save</span>
            </button>
        </div>
    </article>`;

    div.innerHTML = html;

    div.querySelector('div.story-post h2.story-title').textContent = title;

    switch (type) {
    case 'text':
        div.querySelector('div.story-post').insertAdjacentHTML('beforeend',
            '<p class="story-content"></p>'
        );
        div.querySelector('div.story-post p.story-content').textContent = content;
        break;
    case 'image':
        div.querySelector('div.story-post').insertAdjacentHTML('beforeend',
            `<img class="story-image" src="${imagesrc}" data-imagefile="${imagefile}"/>`
        );
        break;
    }

    return div.firstChild;
}

