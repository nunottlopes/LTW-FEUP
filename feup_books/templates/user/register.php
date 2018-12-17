<div id="register-popup" class="overlay">
  <div class="popup">
    <div class="register-box">
      <button class="close" id="close_popup">&times;</button>
      <h1>Sign up</h1>
      
      <form onsubmit="registerHandler(this, event)">
        <input type="text" name="username" placeholder="Username" />
        <input type="email" name="email" placeholder="E-mail" />
        <input type="password" name="password" placeholder="Password" />
        <input type="password" name="password2" placeholder="Retype password" />
        <input type="submit" name="signup_submit" value="Sign me up" />
      </form>

      <div id="register-error"></div>

      <script type="text/javascript" src="javascript/common/register.js" defer></script>

      <p>Already have an account? <a onclick="closeSignUp(); openLogIn()">Log in</a> now.</p>
    </div>
  </div>
</div>