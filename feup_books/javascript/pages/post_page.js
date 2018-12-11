var post_page_post = document.querySelector("#post_page_post");
var storyid = post_page_post.getAttribute("story-id");

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
api.tree.get({parentid:storyid}).then(response => {
    if(response.ok){
        return response.json();
    }
    else{
        window.location.replace("index.php");
        throw response;
    }
})
.then(json => getComments(json.data));


function getStory(story){

    // Article
    var article = document.querySelector(".post_complete");
    let article1 = `<article class="post_complete">
    <header>Posted by ${story.authorname} ${timeDifference(story.createdat)}</header>
        <h1>${story.storyTitle}</h1>
        <p>Husbands ask repeated resolved but laughter debating. She end cordial visitor noisier fat subject general picture. Or if offering confined entrance no. Nay rapturous him see something residence. Highly talked do so vulgar. Her use behaved spirits and natural attempt say feeling. Exquisite mr incommode immediate he something ourselves it of. Law conduct yet chiefly beloved examine village proceed. </p>
        <?php include('templates/common/post_buttons.php')?>`;

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
            <button class="post_button" onclick="comments()"><i class="fa fa-comment"></i> Comments</button>
            <button class="post_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
            <button class="post_button" onclick="share()"><i class="fa fa-share-alt"></i> Share</button>
        </footer>
    </article>`;

    article.innerHTML = article1 + article2 + article3;
}

// Get All Comments
var allComments = "";

function getComments(data){

    // Comments
    var comments = document.querySelector("#post_comments");

    getCommentsFromTree(data, 0);

    comments.innerHTML = allComments;
}

function getCommentsFromTree(data, margin){
    for(let comment in data.children){
        var currentComment = data.children[comment];
        console.log(currentComment);
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

        if(Object.keys(currentComment.children).length > 0){
            margin++;
            getCommentsFromTree(currentComment, margin);
        }
        allComments += '</article>';
    }
}

//tenho de passar entityid e userid para upvotes e afins