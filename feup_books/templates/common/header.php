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

      <div class="login_register_buttons">
       <a href="#login-popup"><button class="log_in_button" type="button">LOG IN</button></a>
       <?php include('templates/user/login.php'); ?>
       <a href="#register-popup"><button class="sign_up_button" type="button">SIGN UP</button></a>
       <?php include('templates/user/register.php'); ?>
      </div>
      
    </header>
