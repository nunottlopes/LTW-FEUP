<?php
  include_once('database/connection.php');
  include_once('database/news.php');
  include_once('database/comments.php');

  $articles = getAllNews();

  include('templates/common/header.php');
  include('templates/news/list_news.php');
  include('templates/common/footer.php');
?>
