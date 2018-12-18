let post_page_post = document.querySelector("#post_page_post");
let comments = document.querySelector("#post_comments");

let settings = {
    sort: document.querySelector(".dropdown_selection").getAttribute("selectionid"),
    limit: 5,
    offset: 0,
    maxdepth: 15
}

getPageContent();

function getPageContent(){
    // Get Post 
    if(!auth) {
        api.story.get({storyid:storyid}).then(response => {
            if(response.ok){
                return response.json();
            }
            else{
                window.location.replace("index.php");
                throw response;
            }
        })
        .then(json => {
            api.channel.get({channelid: json.data.channelid}, [200])
            .then(response => response.json())
            .then( r => updateAside(r.data))
            getStory(json.data);
        });
    } else {
        api.story.get({storyid:storyid, voterid: auth.userid}).then(response => {
            if(response.ok){
                return response.json();
            }
            else{
                window.location.replace("index.php");
                throw response;
            }
        })
        .then(json => {
            api.channel.get({channelid: json.data.channelid}, [200])
            .then(response => response.json())
            .then( r => updateAside(r.data))
            getStory(json.data);
        });
    }

    // Get Comments
    getComments();
}

function getStory(story){
    // Article
    document.querySelector(".post_complete").appendChild(htmlStory(story));

    //Comment Form
    let add_comment_form = document.querySelector("#add_comment");
    const picturefile = (auth ? (auth.picturefile || defaultPicture(auth.userid)) : null);
    const picturesrc = api.imagelink('thumbnail', picturefile);
    if(auth != null){
        add_comment_form.innerHTML += 
        `<img src="${picturesrc}">
        <form action="handlers/add_comment_handler.php?parentid=${story.entityid}&authorid=${auth.userid}" method="post">
            <textarea name="content" placeholder="Add your comment here..."></textarea>
            <input type="submit" value="Add comment" class="submit_comment_button">
        </form>`;
    }
}

function getComments(){
    allComments = "";

    if(auth != null){
        api.tree.get({ascendantid:storyid, voterid:auth.userid, order: settings.sort}, [200])
        .then(response => response.json())
        .then(json => {
            let total = getCommentsFromTree(json.data);
            for(c of total) {
                comments.appendChild(c);
            }
        })
    }
    else{
        api.tree.get({ascendantid:storyid, order: settings.sort}, [200])
        .then(response => response.json())
        .then(json => {
            let total = getCommentsFromTree(json.data);
            for(c of total) {
                comments.appendChild(c);
            }
        })
    }
}

function getCommentsFromTree(data){
    let total = [];
    for(let comment of data){
        let maincomment = htmlComment(comment);
        if(comment.children.length > 0) {
            let div = document.createElement('div');
            let t = getCommentsFromTree(comment.children);
            for(c of t) {
                div.appendChild(c);
            }
            maincomment.appendChild(div);
        }
        total.push(maincomment);
    }
    console.log
    return total;
}

document.querySelectorAll(".dropdown_options > *").forEach(element => {
    element.addEventListener('click', () => {
        comments.innerHTML = "";
        settings.offset = 0;
        settings.sort = element.getAttribute("id");
        getComments();
    });
})

function updateAside(data) {
    let h1 = document.querySelector("#channel_info h1");
    h1.textContent = data.channelname;
    h1.style.cursor = "pointer";
    h1.addEventListener('click', () =>{
        window.location.replace("channel.php?id="+data.channelid);
    })
    document.querySelector("#channel_info h2").textContent =
        data.stories + ((data.stories == 1) ? " Post" : " Posts");
    document.querySelector("#channel_info p").textContent =
        "by " + data.creatorname;
    document.querySelector("#channel_info").style.backgroundImage = 
        `url('images/upload/small/${data.bannerfile}')`;
}

function htmlStory(story) {
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
    const html = `<article id="story${entityid}" class="story story-channelpage post_complete" data-entityid="${entityid}">
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
        <div class="story-post">
            <h2 class="story-title"></h2>
        </div>
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

function htmlComment(comment) {
    // comment
    const entityid = comment.entityid;

    // score-info
    const vote = comment.vote || "";
    const score = vote ? (vote === '+' ? comment.score - 1 : comment.score + 1) : comment.score;
    
    // author-info
    const authorid = comment.authorid;
    const authorname = comment.authorname;
    const authorlink = 'profile.php?id=' + authorid;
    
    const authpic = (auth ? (auth.picturefile || defaultPicture(auth.userid)) : null);
    const picturesrc = api.imagelink('thumbnail', authpic);

    // timestamp
    const createdat = comment.createdat;
    const updatedat = comment.updatedat;
    const timestamp = timeDifference(createdat);

    // comment-post 
    const content = comment.content;

    // comment-save
    const save = comment.save ? 1 : 0;

    const div = document.createElement('div');

    // Main HTML
    const html = `<article id="comment${entityid}" class="comment post_comment" data-entityid="${entityid}">
        <a href="${authorlink}">
            <header>
                <span data-authorid="${authorid}" data-authorname="${authorname}">${authorname}</span>
                <span data-createdat="${createdat}" data-updatedat="${updatedat}">${timestamp}</span>
            </header>
        </a>
        <p class="comment-content">${content}</p>
        <div class="comment-buttons">
            <div class="score-info comment-score" data-vote="${vote}" data-score="${score}">
                <i class="fas fa-arrow-up comment_button" onclick="upvote(this, ${entityid})"></i>
                <span class="score" data-score="${score}" data-score-up="${score+1}" data-score-down="${score-1}"></span>
                <i class="fas fa-arrow-down comment_button" onclick="downvote(this, ${entityid})"></i>
            </div>
            <button class="comment_button" onclick="reply(this, ${entityid})" data-authimg="${picturesrc}">
                <i class="fa fa-comment"></i>
                <span class="comment-reply">Reply</span>
            </button>
            <button class="comment_button comment-save save" onclick="save(this, ${entityid})" data-save="${save}">
                <i class="fa fa-bookmark"></i>
                <span class="comment-save">Save</span>
            </button>
        <div>

    </article>`;

    div.innerHTML = html;

    return div.firstChild;
}