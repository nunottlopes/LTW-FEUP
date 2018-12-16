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

    $('div.story-post h2.story-title', div).textContent = title;

    switch (type) {
    case 'text':
        $('div.story-post', div).insertAdjacentHTML('beforeend',
            '<p class="story-content"></p>'
        );
        $('div.story-post p.story-content', div).textContent = content;
        break;
    case 'image':
        $('div.story-post', div).insertAdjacentHTML('beforeend',
            `<img class="story-image" src="${imagesrc}" data-imagefile="${imagefile}"/>`
        );
    }

    return div.firstChild;
}

