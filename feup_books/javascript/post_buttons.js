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

    console.log("upvote");

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

    console.log("downvote");
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

function updateButtons(userid, entityid){
    var footer = document.querySelector("#post_button_" + entityid);

    if(footer != null){
        api.story.get({storyid:entityid}).then(r => {return r.json()}).then(j => {
            var upvotes = j.data.upvotes;
            var downvotes = j.data.downvotes;
    
            var otherButtons = `<a href="post.php?id=${entityid}"><button class="post_button"><i class="fa fa-comment"></i> ${j.data.count} Comments</button></a>
            <button id="save${entityid}" class="post_button" onclick="save(${entityid})"><i class="fa fa-bookmark"></i> Save</button>
            <button class="post_button" onclick="share(${entityid})"><i class="fa fa-share-alt"></i> Share</button>`;
            
            api.vote.get({userid:userid, entityid:entityid}).then(response => {return response.json()}).then(json => {
                var upvote, downvote;
                if(json.data == false){
                    upvote = `<button id="upvote${entityid}" class="post_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                    downvote = `<button id="downvote${entityid}" class="post_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                }
                else{
                    if(json.data.vote == "+"){
                        upvote = `<button id="upvote${entityid}" class="post_button post_button_selected" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                        downvote = `<button id="downvote${entityid}" class="post_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                    }
                    else{
                        upvote = `<button id="upvote${entityid}" class="post_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                        downvote = `<button id="downvote${entityid}" class="post_button post_button_selected" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                    }
                }
        
                footer.innerHTML = upvote + downvote + otherButtons;
        
            })
        })
    }
    else{
        footer = document.querySelector("#comment_button_" + entityid);

        if(footer != null){
            api.comment.get({commentid:entityid}).then(r => {return r.json()}).then(j => {
                var upvotes = j.data.upvotes;
                var downvotes = j.data.downvotes;

                var otherButtons = `<button class="comment_button" onclick="reply(${entityid})"><i class="fa fa-comment"></i> Reply</button>
                <button class="comment_button" onclick="save(${entityid})"><i class="fa fa-bookmark"></i> Save</button>`
                
                api.vote.get({userid:userid, entityid:entityid}).then(response => {return response.json()}).then(json => {
                    var upvote, downvote;
                    if(json.data == false){
                        upvote = `<button id="upvote${entityid}" class="comment_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                        downvote = `<button id="downvote${entityid}" class="comment_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                    }
                    else{
                        if(json.data.vote == "+"){
                            upvote = `<button id="upvote${entityid}" class="comment_button comment_button_selected" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                            downvote = `<button id="downvote${entityid}" class="comment_button" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                        }
                        else{
                            upvote = `<button id="upvote${entityid}" class="comment_button" onclick="upvote(${entityid})"><i class='fas fa-arrow-up'></i> ${upvotes} Upvotes</button>`;
                            downvote = `<button id="downvote${entityid}" class="comment_button comment_button_selected" onclick="downvote(${entityid})"><i class='fas fa-arrow-down'></i> ${downvotes} Downvotes</button>`;
                        }
                    }
            
                    footer.innerHTML = upvote + downvote + otherButtons;
            
                })
            })
        }
    }



}