<?php
    require_once("../../api/api.php");
    require_once API::entity('comment');

    $parentid = $_GET['parentid'];
    $authorid = $_GET['authorid'];
    $content = $_POST['content'];
    
    $commentid = Comment::create($parentid, $authorid, $content);

    if (!$commentid) {
        HTTPResponse::serverError();
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
?>