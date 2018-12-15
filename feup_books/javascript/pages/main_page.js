let main_page_posts = document.querySelector('#main_page_posts');
let user;

let settings = {
    sort: document.querySelector("#dropdown_selection").getAttribute("selectionid"),
    limit: 5,
    offset: 0
}

api.auth().then(response => {return response.json()}).then(json =>{
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
    api.save.get("userid=6&stories&limit=5")
    api.save.get({userid:user.userid, limit:5, stories:""})
    .then(response => response.json())
    .then(json => favouritePosts(json.data)); 
}

function getStories(data) {
    for(let story in data) {

        let a1 =
        `<article class="post_preview">
            <header>Posted by ${data[story].authorname} ${timeDifference(data[story].createdat)}</header>
            <a href="post.php?id=${data[story].entityid}">
            <h1>${data[story].storyTitle}</h1>`;
        
        let a2 = "";
        switch(data[story].storyType) {
            case "image":
                a2 = `<src src="${data[story].content}" alt="post image">`;
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
        </article>`;

        main_page_posts.innerHTML += a1 + a2 + a3;
        
    }

    if(user != null){
        for(let story in data){
            updateButtons(user.userid, data[story].entityid);
        }
    }

    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10)) {
        settings.offset += settings.limit;
        getStoriesContent();
    }
}

function favouritePosts(data){
    let aside_div = document.querySelector('#aside_favorite_post ul');

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
    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10)) {
        settings.offset += settings.limit;
        getStoriesContent();
    }
}