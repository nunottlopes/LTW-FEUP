<?php
require_once __DIR__ . '/../../../api/api.php';
?>
<!DOCTYPE html>
<html>

  <head>
    <title>Feup Book</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/common/style.css">
    <link rel="stylesheet" href="css/common/overlay.css">
    <link rel="stylesheet" href="css/common/dropdown.css">
    <link rel="stylesheet" href="css/common/tab.css">
    <link rel="stylesheet" href="css/pages/main_page.css">
    <link rel="stylesheet" href="css/pages/channel_page.css">
    <link rel="stylesheet" href="css/pages/profile_page.css">
    <link rel="stylesheet" href="css/pages/post_page.css">
    <link rel="stylesheet" href="css/pages/create_post_page.css">
    <link rel="stylesheet" href="css/pages/create_channel_page.css">
    <link rel="stylesheet" href="css/common/post.css">
    <link rel="stylesheet" href="css/common/aside.css">
    <link rel="stylesheet" href="css/common/override.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.5.0/css/all.css' integrity='sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU' crossorigin='anonymous'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript">
      var FEUPBOOK_CSRF_TOKEN = "<?= $_SESSION['CSRFTOKEN'] ?>";
      var auth = <?= json_encode($auth) ?>;
    </script>
    <script src="javascript/common/api-ajax.js" type="text/javascript"></script>
    <script src="javascript/common/date.js" type="text/javascript"></script>
  </head>

  <body>

    <header>
    <a href="index.php"><img src="images/site/logo.png" class="logo_header"></a>
    <section id="user-header">

    <div class="default_dropdown header-dropdown">
      <div class="dropdown_selection">Channels</div>
      <div class="triangle_down"></div>
      <div class="dropdown_options default-dropdown-content"></div>
    </div>

    <div class="header-shortcuts">
      <a class="header-i" onclick="createPostButton()"><i class="fa fa-sticky-note-o"></i></a>
      <a class="header-i" onclick="createChannelButton()"><i class="fa fa-television"></i></a>
    </div>

    <script src="javascript/header.js" type="text/javascript" defer></script>

    <?php 
      if($auth) {?>

        <div class="dropdown">
          <div id="profile_button">
            <img id="client_image" src="images/users/user.png" alt="User profile picture">
            <p id="client_name"><?= $auth['username'] ?></p>
          </div>
        <?php include('templates/user/dropdown.php'); ?>
        </div>
        <script src="javascript/common/user_header_loggedin.js"></script> 

      <?php } else {?>

        <button id="log_in_button" class="header_button" type="button">LOG IN</button>
        <?php include('templates/user/login.php') ?>
        <button id="sign_up_button" class="header_button" type="button">SIGN UP</button>
        <?php include('templates/user/register.php') ?>
        <script src="javascript/common/user_header.js"></script> 

    <?php }?>
    
    </header>
