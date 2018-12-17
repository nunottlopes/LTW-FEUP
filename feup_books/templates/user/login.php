<div id="login-popup" class="overlay">
  <div class="popup">
    <div class="login-box">
        <button class="close" id="close_popup">&times;</button>
        <h1>Log in</h1>
        
        <form onsubmit="loginHandler(this, event)">
          <input type="text" name="username" placeholder="Username" />
          <input type="password" name="password" placeholder="Password" />
          <input type="submit" name="login_submit" value="Log me in" />
        </form>

        <div id="login-error"></div>

        <script type="text/javascript" src="javascript/user/login.js"></script>

        <p>Don't have an account? <a onclick="closeLogIn(); openSignUp()">Sign up</a> now.</p>
      </div>
  </div>
</div>