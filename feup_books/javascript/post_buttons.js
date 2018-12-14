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

    console.log("upvote");
    console.log(auth.userid);
    console.log(entityid);
    api.vote.put({userid:auth.userid, entityid:entityid}, {vote: '+'});

}

function downvote(entityid) {
    if(auth == null){
        loginpopup();
        return;
    }

    api.vote.put({userid:auth.userid, entityid:entityid}, {vote: '-'});
    console.log("downvote");
}

function comments() {
    console.log("comments");
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

    api.save.put({userid:auth.userid, entityid:entityid});
    console.log("save");
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