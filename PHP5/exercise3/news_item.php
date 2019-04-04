<?php
include_once('database/connection.php');
include_once('database/news.php');
include_once('database/comments.php');

$id = $_GET['id'];

$article = getArticle($id);
$comments = getComments($id);
$numCommentsString = getNumCommentsString($id);

include('templates/common/header.php');
include('templates/news/view_news.php');
include('templates/common/footer.php');
?>
