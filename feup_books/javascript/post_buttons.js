api.auth().then(response => {return response.json()}).then(json =>{
    updateAuth(json.data);
})

var auth = null;
function updateAuth(authentication){
    auth = authentication;
}

function upvote(entityid) {
    if(auth == null){
        loginpopup();
        return;
    }

    api.vote.get({userid:auth.userid, entityid:entityid}).then(response => {return response.json()}).then(json => {
        if(json.data == false || json.data.vote == "-"){
            api.vote.put({userid:auth.userid, entityid:entityid}, {vote: '+'});
        }
        else{
            api.vote.delete({userid:auth.userid, entityid:entityid});
        }
        updateButtons(auth.userid,entityid);
    })
}

function downvote(entityid) {
    if(auth == null){
        loginpopup();
        return;
    }

    api.vote.get({userid:auth.userid, entityid:entityid}).then(response => {return response.json()}).then(json => {
        if(json.data == false || json.data.vote == "+"){
            api.vote.put({userid:auth.userid, entityid:entityid}, {vote: '-'});
        }
        else{
            api.vote.delete({userid:auth.userid, entityid:entityid});
        }
        updateButtons(auth.userid,entityid);
    })
}

function share(storyid) {
    console.log("share");
    console.log(storyid);
}

function save(entityid) {
    if(auth == null){
        loginpopup();
        return;
    }

    //TODO: CHECK IF A POST OR COMMENT IS ALREADY SAVED

    api.save.put({userid:auth.userid, entityid:entityid});
    updateButtons(auth.userid, entityid);
}

function reply(commentid) {
    if(auth == null){
        loginpopup();
        return;
    }

    var comment = document.querySelector("#comment"+commentid);
    var add_comment_element = document.createElement("div");
    add_comment_element.setAttribute("id", "add_comment");
    add_comment_element.innerHTML = `<img src="images/users/user.png">
         <form action="api/public/comment.php?parentid=${commentid}&authorid=${auth.userid}" method="post">
             <textarea name="content" placeholder="Add your comment here..."></textarea>
             <input type="submit" value="Add comment" class="submit_comment_button">
         </form>`;
    
    if(comment.querySelector("#add_comment") == null){
        comment.insertBefore(add_comment_element, comment.children[3]);
    }
    else{
        comment.removeChild(comment.querySelector("#add_comment"));
    }
}

function loginpopup(){
    document.querySelector("#login-popup").style.visibility = "visible";
    document.querySelector("#login-popup").style.opacity = 1;
}

function signuppopup(){
    document.querySelector("#register-popup").style.visibility = "visible";
    document.querySelector("#register-popup").style.opacity = 1;
}

function updateButtons(userid, entityid){
    updatePostButtons(userid, entityid);
    updateCommentButtons(userid, entityid);
}

function updatePostButtons(userid, entityid){
    var footer = document.querySelector("#post_button_" + entityid);

    if(footer != null){
        api.story.get({voterid:userid, storyid:entityid}).then(response => {return response.json()}).then(json => {
            footer.innerHTML = "";
            var upvotes = json.data.upvotes;
            var downvotes = json.data.downvotes;
            
            if(json.data.vote){
                if(json.data.vote == "+"){
                    footer.innerHTML += `<button id="upvote${entityid}" class="post_button post_button_selected" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                    footer.innerHTML += `<button id="downvote${entityid}" class="post_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                }
                else{
                    footer.innerHTML += `<button id="upvote${entityid}" class="post_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                    footer.innerHTML += `<button id="downvote${entityid}" class="post_button post_button_selected" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                }
            }
            else{
                footer.innerHTML += `<button id="upvote${entityid}" class="post_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                footer.innerHTML += `<button id="downvote${entityid}" class="post_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
            }

            footer.innerHTML += `<a href="post.php?id=${entityid}"><button class="post_button"><i class="fa fa-comment"></i> ${json.data.count} Comments</button></a>`;

            if(json.data.save){
                footer.innerHTML += `<button id="save${entityid}" class="post_button post_button_selected" onclick="save(${entityid})"><i class="fa fa-bookmark"></i> Save</button>`;
            }
            else{
                footer.innerHTML += `<button id="save${entityid}" class="post_button" onclick="save(${entityid})"><i class="fa fa-bookmark"></i> Save</button>`;
            }

            footer.innerHTML += `<button class="post_button" onclick="share(${entityid})"><i class="fa fa-share-alt"></i> Share</button>`;
            
        })
    }
}

function updateCommentButtons(userid, entityid){
    var footer = document.querySelector("#comment_button_" + entityid);

    if(footer != null){
        api.comment.get({voterid:userid, commentid:entityid}).then(response => {return response.json()}).then(json => {
            footer.innerHTML = "";
            var upvotes = json.data.upvotes;
            var downvotes = json.data.downvotes;
            
            if(json.data.vote){
                if(json.data.vote == "+"){
                    footer.innerHTML += `<button id="upvote${entityid}" class="comment_button comment_button_selected" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                    footer.innerHTML += `<button id="downvote${entityid}" class="comment_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                }
                else{
                    footer.innerHTML += `<button id="upvote${entityid}" class="comment_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                    footer.innerHTML += `<button id="downvote${entityid}" class="comment_button comment_button_selected" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                }
            }
            else{
                footer.innerHTML += `<button id="upvote${entityid}" class="comment_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                footer.innerHTML += `<button id="downvote${entityid}" class="comment_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
            }

            footer.innerHTML += `<button class="comment_button" onclick="reply(${entityid})"><i class="fa fa-comment"></i> Reply</button>`;

            if(json.data.save){
                footer.innerHTML += `<button id="save${entityid}" class="comment_button comment_button_selected" onclick="save(${entityid})"><i class="fa fa-bookmark"></i> Save</button>`;
            }
            else{
                footer.innerHTML += `<button id="save${entityid}" class="comment_button" onclick="save(${entityid})"><i class="fa fa-bookmark"></i> Save</button>`;
            }
        })
    }
}