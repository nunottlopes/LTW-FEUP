<!DOCTYPE html>
<html>

  <head>
    <title>Feup Book</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/user.css">
    <link rel="stylesheet" href="css/pages/main_page.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.5.0/css/all.css' integrity='sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU' crossorigin='anonymous'>
  </head>

  <body>

    <header>
    <a href="index.php"><img src="images/site/logo.png" class="logo_header"></a>
      <input class="search_bar" type="text" placeholder="Search.." name="search"/>

      <div class="user-buttons">
        <a href="#login-popup" class="user-signup">Login</a>
        <?php include('templates/user/login.php'); ?>
        <a href="#register-popup" class="user-signup">Register</a>
        <?php include('templates/user/register.php'); ?>
      </div>
    </header>
