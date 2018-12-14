let post_page_post = document.querySelector("#post_page_post");
let storyid = post_page_post.getAttribute("story-id");
let user;

api.auth().then(response => {return response.json()}).then(json =>{
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
    api.tree.get({ascendantid:storyid}, [200])
    .then(response => response.json())
    .then(json => getComments(json.data));
}

function getStory(story){
    // Article
    let article = document.querySelector(".post_complete");
    let article1 = `<article class="post_complete">
    <header>Posted by ${story.authorname} ${timeDifference(story.createdat)}</header>
        <h1>${story.storyTitle}</h1>`;

    let article2 = "";
    switch(story.storyType) {
        case "image":
            article2 = `<src src="${story.content}" alt="post image">`;
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
    <button class="post_button" onclick="save(${story.entityid})"><i class="fa fa-bookmark"></i> Save</button>
    <button class="post_button" onclick="share(${story.entityid})"><i class="fa fa-share-alt"></i> Share</button>
        </footer>
        </article>`;

    article.innerHTML = article1 + article2 + article3;

    //Comment Form
    if(user != null){
        document.querySelector("#add_comment").innerHTML = `<img src="images/users/user.png">
        <form action="api/public/comment.php?parentid=${story.entityid}&authorid=${user.userid}" method="post">
            <textarea name="content" placeholder="Add your comment here..."></textarea>
            <input type="submit" value="Add comment" class="submit_comment_button">
        </form>`;
    }
    else{
        document.querySelector("#add_comment").innerHTML = `<h3><a onclick='loginpopup()'>Login</a> or <a onclick='signuppopup()'>Signup</a> to comment the post.</h3>`
    }

    updateButtons(user.userid, story.entityid);
}

// Get All Comments
let allComments = "";

function getComments(data){
    allComments = "";
    // Comments
    let comments = document.querySelector("#post_comments");

    getCommentsFromTree(data);

    comments.innerHTML = allComments;

    updateButtonsComments(data);
}

function getCommentsFromTree(data){
    for(let comment in data){
        let currentComment = data[comment];

        let article = `<article id=comment${currentComment.entityid} class="post_comment">
        <header>${currentComment.authorname}, ${timeDifference(currentComment.updatedat)}</header>
        <p>${currentComment.content}</p>
        <footer id=comment_button_${currentComment.entityid}>
            <button class="comment_button" onclick="upvote(${currentComment.entityid})"><i class='fas fa-arrow-up'></i> ${currentComment.upvotes} Upvotes</button>
            <button class="comment_button" onclick="downvote(${currentComment.entityid})"><i class='fas fa-arrow-down'></i> ${currentComment.downvotes} Downvotes</button>
            <button class="comment_button" onclick="reply(${currentComment.entityid})"><i class="fa fa-comment"></i> Reply</button>
            <button class="comment_button" onclick="save(${currentComment.entityid})"><i class="fa fa-bookmark"></i> Save</button>
        </footer>`;

        allComments += article;
        
        if(currentComment.children.length > 0){
            getCommentsFromTree(currentComment.children);
        }
        allComments += '</article>';
    }
}

function updateButtonsComments(data){
    for(let comment in data){
        
        let currentComment = data[comment];
        updateButtons(user.userid, currentComment.entityid);

        if(currentComment.children.length > 0){
            updateButtonsComments(currentComment.children);
        }
    }
}

// let selected_sort_option = document.querySelector("#dropdown_selection");

// document.querySelector("#top_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "TOP";
//     api.tree.get({ascendantid:storyid, order:"top"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

// document.querySelector("#bot_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "BOT";
//     api.tree.get({ascendantid:storyid, order:"bot"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

// document.querySelector("#new_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "NEW";
//     api.tree.get({ascendantid:storyid, order:"new"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

// document.querySelector("#old_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "OLD";
//     api.tree.get({ascendantid:storyid, order:"old"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

// document.querySelector("#best_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "BEST";
//     api.tree.get({ascendantid:storyid, order:"best"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

// document.querySelector("#controversial_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "CONTROVERSIAL";
//     api.tree.get({ascendantid:storyid, order:"controversial"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

// document.querySelector("#average_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "AVERAGE";
//     api.tree.get({ascendantid:storyid, order:"average"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

// document.querySelector("#hot_dropdown").addEventListener("click", function(){
//     selected_sort_option.content = "HOT";
//     api.tree.get({ascendantid:storyid, order:"hot"}, [200])
//     .then(response => response.json())
//     .then(json => getComments(json.data));
// });

document.querySelectorAll("#dropdown_options > *").forEach(element => {
    element.addEventListener('click', () => {
        let order = element.getAttribute('id');
        api.tree.get({ascendantid:storyid, order: order}, [200])
        .then(response => response.json())
        .then(json => getComments(json.data));
    });
})

function updateAside(data) {
    document.querySelector("#channel_subscription h1").textContent =
        data.channelname;
    document.querySelector("#channel_subscription h2").textContent =
        data.count + ((data.count == 1) ? " Post" : " Posts");
    document.querySelector("#channel_subscription p").textContent =
        "by " + data.creatorname;
}