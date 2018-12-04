<!-- <section id="register">
  <h2>Register</h2>
  <form action="action_register.php" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <span class="hint">Only lowercase, at least 3 characters</span>

    <input type="password" name="password" placeholder="Password" required>
    <span class="hint">One uppercase, 1 symbol, 1 number, at least 8 characters</span>

    <input type="password" name="repeat" placeholder="Repeat Password" required>
    <span class="hint">Must match password</span>

    <input type="submit" value="Register">
  </form>
</section> -->

<div id="register-popup" class="overlay">
  <div class="popup">
    <h2>Register</h2>
        <a href="#" class="close">&times;</a>
        <form action="#" method="post">
            <label>Username:<input type="text" name="username"></label>
            <label>E-mail:<input type="email" name="email"></label>
            <label>Password:<input type="password" name="password"></label>
            <input type="submit" value="Register">
        </form>
  </div>
</div>