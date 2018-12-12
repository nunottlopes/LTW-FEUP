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

var authorid = document.querySelector("#account");
//authorid.getAttribute
api.story.get({authorid:storyid}).then(response => {
  if(response.ok){
      return response.json();
  }
  else{
      window.location.replace("index.php");
      throw response;
  }
})
.then(json => getStory(json.data));

var contentDiv = document.querySelector("#profile_content");

document.querySelector("#my_posts").addEventListener("click", function(){
  var content = "";
  content += '<h1>My Posts</h1>';

  contentDiv.innerHTML = `<h1>My Posts</h1>
  <div class="profile_content_inside">
    <div class="profile_post">
      <a href="#"><h2>Title</h2></a>
      <h5>Posted by Amadeu 4 hours ago</h5>
    </div>
    <div class="profile_post">
      <a href="#"><h2>Title2</h2></a>
      <h5>Posted by Amadeu 4 hours ago</h5>
    </div>
    <div class="profile_post">
      <a href="#"><h2>Title3</h2></a>
      <h5>Posted by Amadeu 4 hours ago</h5>
    </div>
    <div class="profile_post">
      <a href="#"><h2>Title4</h2></a>
      <h5>Posted by Amadeu 4 hours ago</h5>
    </div>
  </div>`;

    //contentDiv.innerHTML =
});

document.querySelector("#my_comments").addEventListener("click", function(){
    contentDiv.innerHTML = `<h1>My Comments</h1>
    <div class="profile_content_inside">
      <div class="profile_post">
        <a href="#"><h2>Comment</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
      <div class="profile_post">
        <a href="#"><h2>Comment2</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
      <div class="profile_post">
        <a href="#"><h2>Comment3</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
      <div class="profile_post">
        <a href="#"><h2>Comment4</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
    </div>`;
});

document.querySelector("#my_channels").addEventListener("click", function(){
  contentDiv.innerHTML = `<h1>My Channels</h1>
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
});

document.querySelector("#my_saved_posts").addEventListener("click", function(){
    contentDiv.innerHTML = `<h1>My Saved Posts</h1>
    <div class="profile_content_inside">
      <div class="profile_post">
        <a href="#"><h2>Title</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
      <div class="profile_post">
        <a href="#"><h2>Title2</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
      <div class="profile_post">
        <a href="#"><h2>Title3</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
      <div class="profile_post">
        <a href="#"><h2>Title4</h2></a>
        <h5>Posted by Amadeu 4 hours ago</h5>
      </div>
    </div>`;
});

document.querySelector("#edit_profile").addEventListener("click", function(){
    contentDiv.innerHTML = `<h1>Edit Profile</h1>
    <div class="profile_content_inside">
    <form action="#" method="get">
      <div id="profile_button">
        <div id="profile_info">
          Username <input type="text" name="username" value="Amadeu Pereira">
          Email <input type="email" name="email" value="amadeupereira@gmail.com">
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
});

document.querySelector("#logout").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>Logout</h1>';
});