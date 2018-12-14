<?php
    require_once("../../api/api.php");

    $commentid = $_GET['commentid'];
    $authorid = $_GET['authorid'];
    $reply = $_POST['comment'];

    $commentid = Comment::create($commentid, $authorid, $reply);

    if (!$commentid) {
        HTTPResponse::serverError();
    }
    else{
        header("Location:" . $_SERVER['HTTP_REFERER']);
    }
?>