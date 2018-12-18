let noMoreStories = false;
let numStories = 0;

let settings = {
    sort: document.querySelector(".dropdown_selection").getAttribute("selectionid"),
    limit: 5,
    offset: 0
}

api.channel.get({channelid: channel_id}, [200, 404])
    .then(response => {
        if(response.ok) {
            return response.json();
        } else {
            window.location.replace("index.php");
            throw response;
        }
    }).then( r => {
        numStories = r.data.stories;
        updateAside(r.data);
        getContent();
});

function getContent() {
    if(!auth) {
        api.story.get({channelid: channel_id,
            order: settings.sort,
            limit: settings.limit,
            offset: settings.offset})
        .then(response => response.json())
        .then(json => getStories(json.data))
    } else {
        api.story.get({channelid: channel_id,
            order: settings.sort,
            limit: settings.limit,
            offset: settings.offset,
            voterid: auth.userid})
        .then(response => response.json())
        .then(json => getStories(json.data))
    }
}

function getStories(data) {

    let channel_page_posts = document.querySelector('#channel_page_posts');

    if(numStories == 0){
        channel_page_posts.innerHTML = `<article class="post_preview">
        <h1>No posts available for this channel.</h1>	
        </article>`;
    }

    for(let story of data) {
        channel_page_posts.appendChild(htmlStoryMainPage(story));
    }

    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10) && (data.length != 0)) {
        settings.offset += settings.limit;
        getContent();
    }

    if(data.length == 0)
        noMoreStories = true;
}

function updateAside(data) {
    document.querySelector("#channel_info h1").textContent =
        data.channelname;
    document.querySelector("#channel_info h2").textContent =
        data.stories + ((data.stories == 1) ? " Post" : " Posts");
    document.querySelector("#channel_info p").textContent =
        "by " + data.creatorname;
    document.querySelector("#channel_info").style.backgroundImage = 
    `url('images/upload/small/${data.bannerfile}')`;
}

document.querySelectorAll(".dropdown_options > *").forEach(element => {
    element.addEventListener('click', () => {
        channel_page_posts.innerHTML = "";
        settings.offset = 0;
        settings.sort = element.getAttribute("id");
        getContent();
    });
})

window.onscroll = () => {
    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10) && (noMoreStories == false)) {
        settings.offset += settings.limit;
        getContent();
    }
}

function htmlStoryMainPage(story) {
    // story
    const entityid = story.entityid;
    const storylink = 'post.php?id=' + entityid;
    
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
    const html = `<article id="story${entityid}" class="story story-channelpage post_preview" data-entityid="${entityid}">
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
