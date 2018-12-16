<?php
require_once __DIR__ . '/../../../api/api.php';
?>
<!DOCTYPE html>
<html>

  <head>
    <title>Feup Book</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/dropdown.css">
    <link rel="stylesheet" href="css/tab.css">
    <link rel="stylesheet" href="css/pages/main_page.css">
    <link rel="stylesheet" href="css/pages/channel_page.css">
    <link rel="stylesheet" href="css/pages/profile_page.css">
    <link rel="stylesheet" href="css/pages/post_page.css">
    <link rel="stylesheet" href="css/pages/create_post_page.css">
    <link rel="stylesheet" href="css/pages/create_channel_page.css">
    <link rel="stylesheet" href="css/post.css">
    <link rel="stylesheet" href="css/aside.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.5.0/css/all.css' integrity='sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU' crossorigin='anonymous'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript">
      var FEUPBOOK_CSRF_TOKEN = "<?= $_SESSION['CSRFTOKEN'] ?>";
      var auth = <?= json_encode($auth) ?>;
    </script>
    <script src="javascript/api-ajax.js" type="text/javascript"></script>
    <script src="javascript/date.js" type="text/javascript"></script>
  </head>

  <body>

    <header>
    <a href="index.php"><img src="images/site/logo.png" class="logo_header"></a>
    <input class="search_bar" type="text" placeholder="Search.." name="search"/>
    <section id="user-header">

    <?php 
      if($auth) {?>

        <div class="dropdown">
          <div id="profile_button">
            <img id="client_image" src="images/users/user.png" alt="User profile picture">
            <p id="client_name"><?= $auth['username'] ?></p>
          </div>
        <?php include('templates/user/dropdown.php'); ?>
        </div>
        <script src="javascript/user_header_loggedin.js"></script> 

      <?php } else {?>

        <button id="log_in_button" class="header_button" type="button">LOG IN</button>
        <?php include('templates/user/login.php') ?>
        <button id="sign_up_button" class="header_button" type="button">SIGN UP</button>
        <?php include('templates/user/register.php') ?>
        <script src="javascript/user_header.js"></script> 

    <?php }?>
    
    </header>
