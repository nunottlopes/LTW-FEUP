document.querySelector('body').style.height = '100%';
let user;

api.user.get({userid: userid}, [200, 404])
.then(response => {
  if(response.ok)
    return response.json()
  else {
    window.location.replace("index.php");
    throw response;
  }
})
.then(json =>{
  user = json.data;
  loadPage();
})

function loadPage(){
  let divs = document.querySelectorAll("#account ul>*");

  for(let i = 0; i < divs.length; i++){
      divs[i].addEventListener("click", function(){
          divs[i].classList.add("profile_options_selected");
          for(let n = 0; n < divs.length; n++){
              if(n != i){
                  divs[n].classList.remove("profile_options_selected");
              }
          }
      });
  }

  const picturefile = user.picturefile || defaultPicture(userid);
  const picturesrc = api.imagelink('thumbnail', picturefile);

  document.querySelector("#account_menu_image").setAttribute('src', picturesrc);
  
  document.querySelector("#account_menu_username").textContent = user.username;

  var arrayContentDiv = [];
  var contentDiv = document.querySelector("#profile_content");

  /////////////// MY STORIES ///////////////
  getMyStories(); //For first page
  function getMyStories(){
    api.story.get({authorid:userid}).then(response => {
      if(response.ok){
          return response.json();
      }
      else{
          window.location.replace("index.php");
          throw response;
      }
    })
    .then(json => {
        let content = "";
        content += `<h1>My Posts</h1>
        <div class="profile_content_inside">`;
  
        if(json.data.length == 0){
          content += `<h3>No posts available. <a href="create_post.php">Create a post now</a>.</h3>`;
        }
        for(let story in json.data){
          content += `<div class="profile_post" id="profile_post_${json.data[story].entityid}" onmouseover="showEditDeleteButton(${json.data[story].entityid})" onmouseout="hideEditDeleteButton(${json.data[story].entityid})">
            <a href="post.php?id=${json.data[story].entityid}"><h2>${json.data[story].storyTitle}</h2></a>
            <div id="edit_delete_object_${json.data[story].entityid}">  
              <a onclick="deletePost(${json.data[story].entityid})"><i class="fa fa-trash-o"></i></a>
            </div>
            <h5>Posted ${timeDifference(json.data[story].createdat)}</h5>
          </div>`;
        }
        content += '</div>';
        
        contentDiv.innerHTML = content;
    });
  }

  document.querySelector("#my_posts").addEventListener("click", function(){
    getMyStories();
  });


  /////////////// MY COMMENTS ///////////////
  document.querySelector("#my_comments").addEventListener("click", function(){
    api.comment.get({authorid:userid}).then(response => {
      if(response.ok){
          return response.json();
      }
      else{
          window.location.replace("index.php");
          throw response;
      }
    })
    .then(json => {
        let content = "";
        content += `<h1>My Comments</h1>
        <div class="profile_content_inside">`;
  
        if(json.data.length == 0){
          content += `<h3>No comments available.</h3>`;
        }
  
        for(let comment of json.data){
          let storyid = comment.storyid;
          content += `<div class="profile_post" id="profile_post_${comment.entityid}" onmouseover="showEditDeleteButton(${comment.entityid})" onmouseout="hideEditDeleteButton(${comment.entityid})">
            <a href="post.php?id=${storyid}#comment${comment.entityid}"><h4> ${comment.content} </h4></a>
            <div id="edit_delete_object_${comment.entityid}">    
              <a onclick="editComment(${comment.entityid})"><i class="fa fa-edit"></i></a>
              <a onclick="deleteComment(${comment.entityid})"><i class="fa fa-trash-o"></i></a>
            </div>
            <h5>Posted ${timeDifference(comment.createdat)}</h5>
          </div>`;
        }
        content += '</div>';
        
        contentDiv.innerHTML = content;
    });   
  });

  /////////////// MY CHANNELS ///////////////
  api.channel.get({creatorid:userid}).then(response => {return response.json()}).then(json => {
    let content = "";
    content += `<h1>My Channels</h1>
    <div class="profile_content_inside">`;

    if(json.data.length == 0){
      content += `<h3>No channels created!</h3>`;
    }

    for(let channel in json.data){
      content += `<div class="profile_post">
        <a href="channel.php?id=${json.data[channel].channelid}"><h2>${json.data[channel].channelname}</h2></a>
      </div>`;
    }
    content += '</div>';
    
    arrayContentDiv["my_channels"] = content;
  })

  document.querySelector("#my_channels").addEventListener("click", function(){
    contentDiv.innerHTML = arrayContentDiv["my_channels"];
  });


  /////////////// MY SAVED POSTS ///////////////
  if(auth && auth.userid == userid) {
  document.querySelector("#my_saved_posts").addEventListener("click", function(){
    api.save.get({userid:userid, all:""}).then(response => {
      if(response.ok){
          return response.json();
      }
      else{
          window.location.replace("index.php");
          throw response;
      }
    })
    .then(json => {
        let content = "";
        content += `<h1>My Saves</h1>
        <div class="profile_content_inside">`;
  
        if(json.data.length == 0){
          content += `<h3>No saved posts or comments available.`;
        }
  
        for(let saved in json.data){
          if(json.data[saved].type == "comment"){
            content += `<div class="profile_post" id="profile_post_${json.data[saved].comment.entityid}" onmouseover="showEditDeleteButton(${json.data[saved].comment.entityid})" onmouseout="hideEditDeleteButton(${json.data[saved].comment.entityid})">`;
            content += `<a href="post.php?id=${json.data[saved].story.entityid}#comment${json.data[saved].comment.parentid}"><h4> ${json.data[saved].comment.content} </h4></a>
              <div id="edit_delete_object_${json.data[saved].comment.entityid}">    
                <a onclick="deleteSave(${userid}, ${json.data[saved].comment.entityid})"><i class="fa fa-trash-o"></i></a>
              </div>
              <h5>Posted ${timeDifference(json.data[saved].comment.updatedat)}</h5>
            </div>`;
          }
          else{
            content += `<div class="profile_post" id="profile_post_${json.data[saved].story.entityid}" onmouseover="showEditDeleteButton(${json.data[saved].story.entityid})" onmouseout="hideEditDeleteButton(${json.data[saved].story.entityid})">`;
            content += `<a href="post.php?id=${json.data[saved].story.entityid}"><h2>${json.data[saved].story.storyTitle}</h2></a>
              <div id="edit_delete_object_${json.data[saved].story.entityid}">  
                <a onclick="deleteSave(${userid}, ${json.data[saved].story.entityid})"><i class="fa fa-trash-o"></i></a>
              </div>
              <h5>Posted ${timeDifference(json.data[saved].story.updatedat)}</h5>
            </div>`;
          }
        }
        content += '</div>';
        
        contentDiv.innerHTML = content;
    });
  });

  /////////////// EDIT PROFILE ///////////////
  document.querySelector("#edit_profile").addEventListener("click", function(){
    contentDiv.innerHTML = `<h1>Edit Profile</h1>
      <div class="profile_content_inside">
      <form>
        <div id="profile_button">
          <div id="profile_info">
            Username <input type="text" name="username" value="${user.username}" disabled="disabled"/>
            Email <input type="email" name="email" value="${user.email}" disabled="disabled"/>
            New Password <input type="password" name="password"/>
            Retype Password <input type="password" name="repeat_password"/>
            Update Profile Picture <input type="file" name="upload-file"/>
          </div>
          <div id="button_profile">
            <input type="submit" value="Save changes"/>
          </div>
        </div>
      </form>
      <button class="delete_profile">Delete Account</button>
      </div>`;

      let edit_profile_form = document.querySelector(".profile_content_inside");
      edit_profile_form.addEventListener('submit', event => {
        event.preventDefault();
        let pass = edit_profile_form.querySelector('#profile_info > input[name="password"]').value;
        let r_pass = edit_profile_form.querySelector('#profile_info > input[name="repeat_password"]').value;
        let img = edit_profile_form.querySelector('#profile_info > input[name="upload-file"]').files[0];
        
        if(pass == "" && r_pass != "") alert('Please type new password.');
        else if(pass != "" && r_pass == "") alert('Please retype new password.');
        else if(pass != r_pass) alert("New password and retyped password don't match.")
        else if(pass != "" && r_pass != "" && img == undefined){
          api.user.patch({userid: userid}, {password: r_pass}, [200, 400]).then(response => response.json()).then(function(json) {
            if (json.status === 200) {
              return window.location.reload(true);
            }
            if (json.status === 400) {
                alert(json.message);
            }
          });
        }
        else if(pass == "" && r_pass == "" && img != undefined){
          const formData = new FormData(event.target);
          api.fetch('upload', '', {
            method: 'POST',
            body: formData,
            contentType: false,
            processData: false,
          }).then(r => r.json())
          .then( r => api.user.patch({userid: userid}, {imageid: r.id}, [200, 400]).then(() => {
            return window.location.reload(true);
          }));
        }
        else if(pass != "" && r_pass != "" && img != undefined){
          const formData = new FormData(event.target);
          api.fetch('upload', '', {
            method: 'POST',
            body: formData,
            contentType: false,
            processData: false,
          }).then(r => r.json())
          .then( r => api.user.patch({userid: userid}, {password: r_pass, imageid: r.id}, [200, 400]).then(response => response.json()).then(function(json) {
            if (json.status === 200) {
              return window.location.reload(true);
            }
            if (json.status === 400) {
                alert(json.message);
            }
          }));
        }
        else{
          alert("Fill the form please.");
        }
      })

      document.querySelector(".delete_profile").addEventListener('click', () =>{
        api.logout().then(api.user.delete({userid: user.userid})).then(() => window.location.replace("index.php"));
      })
  });


  /////////////// LOG OUT ///////////////
  document.querySelector("#logout").addEventListener("click", function(){
      api.logout();
      window.location.reload();
  });
}
}

function showEditDeleteButton(entityid){
  if(auth && auth.userid == userid)
  document.querySelector("#edit_delete_object_"+entityid).style.display = "inline";
}

function hideEditDeleteButton(entityid){
  document.querySelector("#edit_delete_object_"+entityid).style.display = "none";
}

function deletePost(entityid){
  if(auth && auth.userid == userid) {
    document.querySelector(".profile_content_inside").removeChild(document.querySelector("#profile_post_"+entityid));
    api.story.delete({storyid: entityid});
  }
}

function deleteComment(entityid){
  document.querySelector(".profile_content_inside").removeChild(document.querySelector("#profile_post_"+entityid));
  api.comment.delete({commentid: entityid});
}

function editComment(entityid){
  if(auth && auth.userid != userid) return;
  let div = document.querySelector(".profile_content_inside");
  let child = document.querySelector("#profile_post_"+entityid);
  let content = child.querySelector("a > h4").textContent;

  let form = document.querySelector("#form_update_comment_" + entityid);
  if(form){
    div.removeChild(form);
  }
  else{
    let newform = document.createElement('form');
    newform.setAttribute('id', "form_update_comment_"+entityid);
    newform.setAttribute('class', "form_update_comment");
    newform.setAttribute('action', "handlers/update_comment_handler.php?commentid="+entityid);
    newform.setAttribute('method', 'post');
    newform.innerHTML = `<textarea name="content">${content}</textarea>
      <input type="submit" value="Update comment" class="submit_comment_button">`;
  
    div.insertBefore(newform, child.nextSibling);
  }
}

function deleteSave(userid, entityid){
  if(auth && auth.userid != userid) return;
  document.querySelector(".profile_content_inside").removeChild(document.querySelector("#profile_post_"+entityid));
  api.save.delete({userid:userid, entityid: entityid});
}