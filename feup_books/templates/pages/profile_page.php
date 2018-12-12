<section id="account" user-id="<?php echo $_GET['id']?>" class="page">
  <nav id="account_menu">
    <div id="image_name">
      <img src="images/users/user.png" alt="">
      <h1>Amadeu Pereira</h1>
    </div>
    <ul>
      <div id="my_posts" class="profile_options profile_options_selected">
        <i class="fa fa-newspaper-o"></i><li>My Posts</li>
        </div>
      <div id="my_comments" class="profile_options">
        <i class="fa fa-comments-o"></i><li>My Comments</li>
        </div>
      <div id="my_channels" class="profile_options">
        <i class="fa fa-television"></i><li>My Channels</li>
        </div>
      <div id="my_saved_posts" class="profile_options">
        <i class="fa fa-check-square-o"></i><li>My Saved Posts</li>
        </div>
      <div id="edit_profile" class="profile_options">
        <i class="fa fa-edit"></i><li>Edit profile</li>
        </div>
      <div id="logout" class="profile_options">
        <i class="fa fa-sign-out"></i><li>Logout</li>
        </div>
    </ul>
  </nav>

  <div class="profile_content" id="profile_content">
  </div>

  <script src="javascript/profile.js"></script>

</section>