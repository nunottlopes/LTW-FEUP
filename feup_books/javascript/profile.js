api.auth().then(response => {return response.json()}).then(json =>{
  if(json.data == null){
    window.location.replace("index.php");
  }
  else{
    loadPage(json.data);
  }
})

function loadPage(user){
  var userid = user.userid;
  var divs = document.querySelectorAll("#account ul>*");

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

  //TODO: FALTA ALTERAR A IMAGEM TBM
  document.querySelector("#account_menu_username").textContent = user.username;

  var arrayContentDiv = [];
  var contentDiv = document.querySelector("#profile_content");

  /////////////// MY STORIES ///////////////
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
      var content = "";
      content += `<h1>My Posts</h1>
      <div class="profile_content_inside">`;
      for(let story in json.data){
        content += `<div class="profile_post">
          <a href="post.php?id=${json.data[story].entityid}"><h2>${json.data[story].storyTitle}</h2></a>
          <h5>Posted ${timeDifference(json.data[story].createdat)}</h5>
        </div>`;
      }
      content += '</div>';
      
      arrayContentDiv["my_posts"] = content;
      contentDiv.innerHTML = content;
  });

  document.querySelector("#my_posts").addEventListener("click", function(){
    contentDiv.innerHTML = arrayContentDiv["my_posts"];
  });


  /////////////// MY COMMENTS ///////////////
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
      var content = "";
      content += `<h1>My Posts</h1>
      <div class="profile_content_inside">`;
      for(let comment in json.data){
        var storyid = json.data[comment].storyid;
        content += `<div class="profile_post">
          <a href="post.php?id=${storyid}#comment${json.data[comment].parentid}"><h4> ${json.data[comment].content} </h4></a>
          <h5>Posted ${timeDifference(json.data[comment].createdat)}</h5>
        </div>`;
      }
      content += '</div>';
      
      arrayContentDiv["my_comments"] = content;
  });

  document.querySelector("#my_comments").addEventListener("click", function(){
    contentDiv.innerHTML = arrayContentDiv["my_comments"];
  });

  /////////////// MY CHANNELS ///////////////

  //TODO: faltam a função api.channel.get que devolve os channels subscribes por um user

  document.querySelector("#my_channels").addEventListener("click", function(){
    arrayContentDiv["my_channels"] = `<h1>My Channels</h1>
      <div class="profile_content_inside">
        <div class="profile_post">
          <a href="#"><h2>Channel1</h2></a>
          <h5>Subscribed 4 hours ago</h5>
        </div>
        <div class="profile_post">
          <a href="#"><h2>Channel2</h2></a>
          <h5>Subscribed 4 hours ago</h5>
        </div>
        <div class="profile_post">
          <a href="#"><h2>Channel3</h2></a>
          <h5>Subscribed 4 hours ago</h5>
        </div>
        <div class="profile_post">
          <a href="#"><h2>Channel4</h2></a>
          <h5>Subscribed 4 hours ago</h5>
        </div>
      </div>`;
    contentDiv.innerHTML = arrayContentDiv["my_channels"];
  });


  /////////////// MY SAVED POSTS ///////////////
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
      var content = "";
      content += `<h1>My Saved Posts</h1>
      <div class="profile_content_inside">`;
      for(let saved in json.data){
        if(!isNaN(json.data[saved].parentid)){
          content += `<div class="profile_post">
          <a href="post.php?id=${json.data[saved].storyid}#comment${json.data[saved].parentid}"><h4> ${json.data[saved].content} </h4></a>
          <h5>Posted ${timeDifference(json.data[saved].createdat)}</h5>
        </div>`;
        }
        else{
          content += `<div class="profile_post">
          <a href="post.php?id=${json.data[saved].entityid}"><h2>${json.data[saved].storyTitle}</h2></a>
          <h5>Posted ${timeDifference(json.data[saved].createdat)}</h5>
        </div>`;
        }
      }
      content += '</div>';
      
      arrayContentDiv["my_saved_posts"] = content;
  });

  document.querySelector("#my_saved_posts").addEventListener("click", function(){
    contentDiv.innerHTML = arrayContentDiv["my_saved_posts"];
  });


  /////////////// EDIT PROFILE ///////////////
  document.querySelector("#edit_profile").addEventListener("click", function(){
    arrayContentDiv["edit_profile"] = `<h1>Edit Profile</h1>
      <div class="profile_content_inside">
      <form action="handlers/updateProfile_handler.php" method="post">
        <div id="profile_button">
          <div id="profile_info">
            Username <input type="text" name="username" value="${user.username}">
            Email <input type="email" name="email" value="${user.email}">
            New Password <input type="password" name="password">
            Retype Password <input type="password" name="repeat_password">
            Update Profile Picture <input type="file" name="fileToUpload">
          </div>
          <div id="button_profile">
            <input type="submit" value="Save changes">
          </div>
        </div>
      </form>
      </div>`;

    contentDiv.innerHTML = arrayContentDiv["edit_profile"];

  });

  /////////////// LOG OUT ///////////////
  document.querySelector("#logout").addEventListener("click", function(){
      api.logout();
      window.location.reload();
  });
}