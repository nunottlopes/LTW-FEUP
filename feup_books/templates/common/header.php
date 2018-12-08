<!DOCTYPE html>
<html>

  <head>
    <title>Feup Book</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/dropdown.css">
    <link rel="stylesheet" href="css/pages/main_page.css">
    <link rel="stylesheet" href="css/pages/profile_page.css">
    <link rel="stylesheet" href="css/pages/post_page.css">
    <link rel="stylesheet" href="css/post.css">
    <link rel="stylesheet" href="css/aside.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.5.0/css/all.css' integrity='sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU' crossorigin='anonymous'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>

  <body>

    <header>
    <a href="index.php"><img src="images/site/logo.png" class="logo_header"></a>
    <input class="search_bar" type="text" placeholder="Search.." name="search"/>
    <section id="user-header">
      <div class="dropdown">
        <!-- <div id="profile_button">
        <img src="images/users/user.png" alt="User profile picture">
        <p> Amadeu Pereira </p>
        </div>
        <?php //include('templates/user/dropdown.php'); ?>
      </div> -->

      <button id="log_in_button" class="header_button" type="button">LOG IN</button>
      <?php include('templates/user/login.php'); ?>
      <button id="sign_up_button" class="header_button" type="button">SIGN UP</button>
      <?php include('templates/user/register.php'); ?>

      <script src="javascript/user_header.js"></script> 
      
    </header>
