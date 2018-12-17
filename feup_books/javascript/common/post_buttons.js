function upvote(that, entityid) {
    if(auth == null){
        loginpopup();
        return;
    }
    let score = that.closest('div.score-info');

    if(score.dataset.vote == "+") {
        score.dataset.vote = "";
        api.vote.delete({userid:auth.userid, entityid:entityid});
    } else {
        score.dataset.vote = "+";
        api.vote.put({userid:auth.userid, entityid:entityid}, {vote: '+'});
    }
}

function downvote(that, entityid) {
    if(auth == null){
        loginpopup();
        return;
    }

    let score = that.closest('div.score-info');

    if(score.dataset.vote == "-") {
        score.dataset.vote = "";
        api.vote.delete({userid:auth.userid, entityid:entityid});
    } else {
        score.dataset.vote = "-";
        api.vote.put({userid:auth.userid, entityid:entityid}, {vote: '-'});
    }
}

function save(that, entityid) {
    if(auth == null){
        loginpopup();
        return;
    }

    let save = that.closest('button.save');
    console.log(save);

    if(save.dataset.save == "1") {
        save.dataset.save = "0";
        api.save.delete({userid:auth.userid, entityid:entityid});
    } else {
        save.dataset.save = "1";   
        api.save.put({userid:auth.userid, entityid:entityid});
    }
}

function reply(that, commentid) {
    if(auth == null){
        loginpopup();
        return;
    }
    let comment = that.closest('article.comment');
    var add_comment_element = document.createElement("div");
    add_comment_element.setAttribute("id", "add_comment");
    add_comment_element.innerHTML = `<img src="${that.dataset.authimg}">
         <form action="handlers/add_comment_handler.php?parentid=${commentid}&authorid=${auth.userid}" method="post">
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