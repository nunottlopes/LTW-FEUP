<div id="login-popup" class="overlay">
  <div class="popup">
    <div class="login-box">
        <button class="close" id="close_popup">&times;</button>
        <h1>Log in</h1>
        
        <form action="handlers/login_handler.php" method="post">
          <input type="text" name="username" placeholder="Username" />
          <input type="password" name="password" placeholder="Password" />
          <input type="submit" name="login_submit" value="Log me in" />
        </form>
      </div>
  </div>
</div>