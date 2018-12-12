let channel_id = document.querySelector("#channel_page").getAttribute("channel_id");
if(channel_id == "") window.location.replace("index.php");

let nposts;
let creatorname;
let channel_data;

api.channel.get({channelid: channel_id})
.then(response => {
    if(response.ok) {
        return response.json();
    } else {
        window.location.replace("index.php");
        throw response;
    }
})
.then(r => {
    channel_data = r.data;
    api.story.get({channelid: channel_id})
    .then(response => response.json())
    .then(json => {
        getStories(json.data);
        api.user.get({userid: channel_data.creatorid}, [200])
        .then(response => response.json())
        .then(r => {creatorname = r.data.username; updateAside()})
    })
});



function getStories(data) {
    nposts = data.length;

    let main_page_posts = document.querySelector('#channel_page_posts');
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
            <footer>
                <button class="post_button" onclick="upvote()"><i class='fas fa-arrow-up'></i> ${data[story].upvotes} Upvotes</button>
                <button class="post_button" onclick="downvote()"><i class='fas fa-arrow-down'></i> ${data[story].downvotes} Downvotes</button>
                <button class="post_button" onclick="comments()"><i class="fa fa-comment"></i> Comments</button>
                <button class="post_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
                <button class="post_button" onclick="share()"><i class="fa fa-share-alt"></i> Share</button>
            </footer>
        </article>`;

        main_page_posts.innerHTML += a1 + a2 + a3;
        
    }
}

function updateAside() {
    document.querySelector("#channel_subscription h1").textContent =
        channel_data.channelname;
    document.querySelector("#channel_subscription h2").textContent =
        nposts + ((nposts == 1) ? " Post" : " Posts");
    document.querySelector("#channel_subscription p").textContent =
        "by " + creatorname;
}