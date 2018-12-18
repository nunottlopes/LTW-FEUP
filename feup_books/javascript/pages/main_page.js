let main_page_posts = document.querySelector('#main_page_posts');
let noMoreStories = false;

let settings = {
    sort: document.querySelector(".dropdown_selection").getAttribute("selectionid"),
    limit: 5,
    offset: 0
}

getContent();

function getContent() {
    getStoriesContent();
    getFavouritePostsContent();
}

function getStoriesContent(){
    if(!auth) {
        api.story.get({all: 1,
            order: settings.sort,
            limit: settings.limit,
            offset: settings.offset}, [200])
        .then(response => response.json())
        .then(json => getStories(json.data));
    } else {
        api.story.get({all: 1,
            order: settings.sort,
            limit: settings.limit,
            offset: settings.offset,
            voterid: auth.userid}, [200])
        .then(response => response.json())
        .then(json => getStories(json.data));
    }
}

function getFavouritePostsContent(){
    if(auth != null){
        api.save.get({userid:auth.userid, limit:5, stories:""})
        .then(response => response.json())
        .then(json => favouritePosts(json.data)); 
    }
    else{
        document.querySelector('#aside_favorite_post').style.display = "none";
    }
}

function getStories(data) {
    for(let story of data) {
        main_page_posts.appendChild(htmlStoryMainPage(story));
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


document.querySelectorAll(".dropdown_options > *").forEach(element => {
    element.addEventListener('click', () => {
        main_page_posts.innerHTML = "";
        settings.offset = 0;
        settings.sort = element.getAttribute("id");
        getStoriesContent();
    });
})

window.onscroll = () => { // REPETIDA
    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10) && (noMoreStories == false)) {
        settings.offset += settings.limit;
        getStoriesContent();
    }
}

function createPostButton(){
    if(auth != null)
        window.location.replace("create_post.php");
    else{
        openLogIn();
    }
}

function createChannelButton(){
    if(auth != null)
        window.location.replace("create_channel.php");
    else{
        openLogIn();
    }
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

    const bannersrc = api.imagelink('thumbnail', bannerfile);

    // score-info
    const vote = story.vote || "";
    const score = vote ? (vote === '+' ? story.score - 1 : story.score + 1) : story.score;
    
    // author-info
    const authorid = story.authorid;
    const authorname = story.authorname;
    const picturefile = story.picturefile || defaultPicture(story.authorid);
    const authorlink = 'profile.php?id=' + authorid;

    const picturesrc = api.imagelink('thumbnail', picturefile);

    // timestamp
    const createdat = story.createdat;
    const updatedat = story.updatedat;
    const timestamp = timeDifference(createdat);

    // story-post 
    const type = story.storyType;
    const title = story.storyTitle;
    const content = story.content;
    const imagefile = story.imagefile;
    const imagesrc = api.imagelink('original', imagefile);

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
            <i class="fas fa-arrow-up" onclick="upvote(this, ${entityid})"></i>
            <span class="score" data-score="${score}" data-score-up="${score+1}" data-score-down="${score-1}"></span>
            <i class="fas fa-arrow-down" onclick="downvote(this, ${entityid})"></i>
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
        <a href=${storylink}>
            <div class="story-post">
                <h2 class="story-title"></h2>
            </div>
        </a>
        <div class="story-buttons">
            <a href="${storylink}" class="story-comments">
                <button class="post_button">
                    <i class="fa fa-comment"></i>
                    <span class="story-count" data-count="${count}">${count} Comments</span>
                </button>
            </a>
            <button class="post_button story-save save" onclick="save(this, ${entityid})" data-save="${save}">
                <i class="fa fa-bookmark"></i>
                <span class="story-save">Save</span>
            </button>
        <div>
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

