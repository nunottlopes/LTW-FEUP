var post_page_post = document.querySelector("#post_page_post");
var storyid = post_page_post.getAttribute("story-id");

// var user;
// api.user.get().then(response => {
//     return response.json();
// })
// .then(json => {user = json.data; console.log("ok"); getPageContent()})

getPageContent();

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
    .then(json => getStory(json.data));

    // Get Comments
    api.tree.get({ascendantid:storyid}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
}

function getStory(story){
    // Article
    var article = document.querySelector(".post_complete");
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

    // TODO: falta adicionar o número de comments na post_page
    let article3 = `<footer>
            <button class="post_button" onclick="upvote()"><i class='fas fa-arrow-up'></i> ${story.upvotes} Upvotes</button>
            <button class="post_button" onclick="downvote()"><i class='fas fa-arrow-down'></i> ${story.downvotes} Downvotes</button>
            <button class="post_button" onclick="comments()"><i class="fa fa-comment"></i> ${story.count} Comments</button>
            <button class="post_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
            <button class="post_button" onclick="share()"><i class="fa fa-share-alt"></i> Share</button>
        </footer>
    </article>`;

    article.innerHTML = article1 + article2 + article3;
    console.log(article.innerHTML);
}

// Get All Comments
var allComments = "";

function getComments(data){
    allComments = "";
    // Comments
    var comments = document.querySelector("#post_comments");

    getCommentsFromTree(data);

    comments.innerHTML = allComments;
}

function getCommentsFromTree(data){
    for(let comment in data){
        var currentComment = data[comment];

        var article = `<article id=${currentComment.entityid} class="post_comment">
        <header>${currentComment.authorname}, ${timeDifference(currentComment.updatedat)}</header>
        <p>${currentComment.content}</p>
        <footer>
            <button class="comment_button" onclick="upvote()"><i class='fas fa-arrow-up'></i> ${currentComment.upvotes} Upvotes</button>
            <button class="comment_button" onclick="downvote()"><i class='fas fa-arrow-down'></i> ${currentComment.downvotes} Downvotes</button>
            <button class="comment_button" onclick="reply()"><i class="fa fa-comment"></i> Reply</button>
            <button class="comment_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
        </footer>`;

        allComments += article;

        if(currentComment.children.length > 0){
            getCommentsFromTree(currentComment.children);
        }
        allComments += '</article>';
    }
}

//tenho de passar entityid e userid para upvotes e afins
var selected_sort_option = document.querySelector("#dropdown_selection");

document.querySelector("#top_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "TOP";
    api.tree.get({ascendantid:storyid, order:"top"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});

document.querySelector("#bot_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "BOT";
    api.tree.get({ascendantid:storyid, order:"bot"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});

document.querySelector("#new_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "NEW";
    api.tree.get({ascendantid:storyid, order:"new"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});

document.querySelector("#old_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "OLD";
    api.tree.get({ascendantid:storyid, order:"old"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});

document.querySelector("#best_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "BEST";
    api.tree.get({ascendantid:storyid, order:"best"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});

document.querySelector("#controversial_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "CONTROVERSIAL";
    api.tree.get({ascendantid:storyid, order:"controversial"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});

document.querySelector("#average_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "AVERAGE";
    api.tree.get({ascendantid:storyid, order:"average"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});

document.querySelector("#hot_dropdown").addEventListener("click", function(){
    selected_sort_option.content = "HOT";
    api.tree.get({ascendantid:storyid, order:"hot"}).then(response => {
        if(response.ok){
            return response.json();
        }
        else{
            window.location.replace("index.php");
            throw response;
        }
    })
    .then(json => getComments(json.data));
});