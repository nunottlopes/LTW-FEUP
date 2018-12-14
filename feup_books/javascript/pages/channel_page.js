let channel_id = document.querySelector("#channel_page").getAttribute("channel_id");

let settings = {
    sort: document.querySelector("#dropdown_selection").getAttribute("selectionid"),
    limit: 5,
    offset: 0
}

api.channel.get({channelid: channel_id})
    .then(response => {
        if(response.ok) {
            return response.json();
        } else {
            window.location.replace("index.php");
            throw response;
        }
    }).then( r => {
        updateAside(r.data);
        getContent();
    })

function getContent() {
    api.story.get({channelid: channel_id, order: settings.sort, limit: settings.limit, offset: settings.offset})
    .then(response => response.json())
    .then(json => getStories(json.data))
}

function getStories(data) {
    nposts = data.length;

    let channel_page_posts = document.querySelector('#channel_page_posts');
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

        channel_page_posts.innerHTML += a1 + a2 + a3;
        
    }
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

document.querySelectorAll("#dropdown_options > *").forEach(element => {
    element.addEventListener('click', () => {
        channel_page_posts.innerHTML = "";
        settings.offset = 0;
        settings.sort = element.getAttribute("id");
        getContent();
    });
})

window.onscroll = () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        settings.offset += settings.limit;
        getContent();
    }
}
