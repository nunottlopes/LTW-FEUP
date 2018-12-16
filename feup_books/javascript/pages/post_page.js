let post_page_post = document.querySelector("#post_page_post");
let storyid = post_page_post.getAttribute("story-id");
let comments = document.querySelector("#post_comments");
let user;

let settings = {
    sort: document.querySelector("#dropdown_selection").getAttribute("selectionid"),
    limit: 5,
    offset: 0,
    maxdepth: 5
}

api.auth().then(response => response.json()).then(json =>{
    user = json.data;
    getPageContent();
})

function getPageContent(){
    // Get Post 
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

    // Get Comments
    getComments();
}

function getStory(story){
    // Article
    let article = document.querySelector(".post_complete");
    let article1 = `<article class="post_complete">
    <header>Posted by ${story.authorname} ${timeDifference(story.createdat)}</header>
        <h1>${story.storyTitle}</h1>`;

    let article2 = "";
    console.log(story);
    switch(story.storyType) {
        case "image":
            article2 = `<img src="images/upload/original/${story.imagefile}" alt="post image">`;
            break;
        case "text":
            article2 = `<p>${story.content}</p>`;
            break;
        default:
            break;
    }

    let article3 = `<footer id=post_button_${story.entityid}>
    <button class="post_button" onclick="upvote(${story.entityid})"><i class='fas fa-arrow-up'></i> ${story.upvotes} Upvotes</button>
    <button class="post_button" onclick="downvote(${story.entityid})"><i class='fas fa-arrow-down'></i> ${story.downvotes} Downvotes</button>
    <button class="post_button"><i class="fa fa-comment"></i> ${story.count} Comments</button>
    <button class="post_button" id="save${story.entityid}" onclick="save(${story.entityid})"><i class="fa fa-bookmark"></i> Save</button>
    <button class="post_button" onclick="share(${story.entityid})"><i class="fa fa-share-alt"></i> Share</button>
        </footer>
        </article>`;

    article.innerHTML = article1 + article2 + article3;

    //Comment Form
    if(user != null){
        document.querySelector("#add_comment").innerHTML = `<img src="images/users/user.png">
        <form action="handlers/add_comment_handler.php?parentid=${story.entityid}&authorid=${user.userid}" method="post">
            <textarea name="content" placeholder="Add your comment here..."></textarea>
            <input type="submit" value="Add comment" class="submit_comment_button">
        </form>`;
    }
    else{
        document.querySelector("#add_comment").innerHTML = `<h3><a onclick='loginpopup()'>Login</a> or <a onclick='signuppopup()'>Signup</a> to comment the post.</h3>`
    }

    updateButtons(user.userid, story.entityid);
}

// // Get All Comments
var allComments;

function getComments(){
    allComments = "";

    if(user != null){
        // api.tree.get({ascendantid:storyid, voterid:user.userid, order: settings.sort, limit: settings.limit, offset: settings.offset, maxdepth: settings.maxdepth}, [200])
        api.tree.get({ascendantid:storyid, voterid:user.userid, order: settings.sort}, [200])
        .then(response => response.json())
        .then(json => {
            getCommentsFromTree(json.data);

            comments.innerHTML = allComments;

            updateButtonsComments(json.data);
            
            // if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10)) {
            //     settings.offset += settings.limit;
            //     getComments();
            // }
        })
    }
    else{
        api.tree.get({ascendantid:storyid, order: settings.sort}, [200])
        //api.tree.get({ascendantid:storyid, order: settings.sort, limit: settings.limit, offset: settings.offset, maxdepth: settings.maxdepth}, [200])
        .then(response => response.json())
        .then(json => {
            getCommentsFromTree(json.data);
        })
    }
}

function getCommentsFromTree(data){
    for(let comment in data){
        let currentComment = data[comment];

        let article = `<article id=comment${currentComment.entityid} class="post_comment">
        <header>${currentComment.authorname}, ${timeDifference(currentComment.updatedat)}</header>
        <p>${currentComment.content}</p>
        <footer id=comment_button_${currentComment.entityid}>
            <button id="upvote${currentComment.entityid}" class="comment_button" onclick="upvote(${currentComment.entityid})"><i class='fas fa-arrow-up'></i> ${currentComment.upvotes} Upvotes</button>
            <button id="downvote${currentComment.entityid}" class="comment_button" onclick="downvote(${currentComment.entityid})"><i class='fas fa-arrow-down'></i> ${currentComment.downvotes} Downvotes</button>
            <button class="comment_button" onclick="reply(${currentComment.entityid})"><i class="fa fa-comment"></i> Reply</button>
            <button id="save${currentComment.entityid}" class="comment_button" onclick="save(${currentComment.entityid})"><i class="fa fa-bookmark"></i> Save</button>
        </footer>`;

        allComments += article;
        
        if(currentComment.children.length > 0){
            getCommentsFromTree(currentComment.children);
        }
        allComments += '</article>';
    }
}

function updateButtonsComments(data){
    let footer;
    for(let comment in data){
        
        let currentComment = data[comment];
        footer = document.querySelector("#comment_button_" + currentComment.entityid);

        footer.innerHTML = "";
        let upvotes = currentComment.upvotes;
        let downvotes = currentComment.downvotes;
        
        if(currentComment.vote){
            if(currentComment.vote == "+"){
                footer.innerHTML += `<button id="upvote${currentComment.entityid}" class="comment_button comment_button_selected" onclick="upvote(${currentComment.entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                footer.innerHTML += `<button id="downvote${currentComment.entityid}" class="comment_button" onclick="downvote(${currentComment.entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
            }
            else{
                footer.innerHTML += `<button id="upvote${currentComment.entityid}" class="comment_button" onclick="upvote(${currentComment.entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                footer.innerHTML += `<button id="downvote${currentComment.entityid}" class="comment_button comment_button_selected" onclick="downvote(${currentComment.entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
            }
        }
        else{
            footer.innerHTML += `<button id="upvote${currentComment.entityid}" class="comment_button" onclick="upvote(${currentComment.entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
            footer.innerHTML += `<button id="downvote${currentComment.entityid}" class="comment_button" onclick="downvote(${currentComment.entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
        }

        footer.innerHTML += `<button class="comment_button" onclick="reply(${currentComment.entityid})"><i class="fa fa-comment"></i> Reply</button>`;

        if(currentComment.save){
            footer.innerHTML += `<button id="save${currentComment.entityid}" class="comment_button comment_button_selected" onclick="save(${currentComment.entityid})"><i class="fa fa-bookmark"></i> Save</button>`;
        }
        else{
            footer.innerHTML += `<button id="save${currentComment.entityid}" class="comment_button" onclick="save(${currentComment.entityid})"><i class="fa fa-bookmark"></i> Save</button>`;
        }

        if(currentComment.children.length > 0){
            updateButtonsComments(currentComment.children);
        }
    }
}

document.querySelectorAll("#dropdown_options > *").forEach(element => {
    element.addEventListener('click', () => {
        comments.innerHTML = "";
        settings.offset = 0;
        settings.sort = element.getAttribute("id");
        getComments();
    });
})

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

// window.onscroll = () => {
//     if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight-10)) {
//         settings.offset += settings.limit;
//         getComments();
//     }
// }