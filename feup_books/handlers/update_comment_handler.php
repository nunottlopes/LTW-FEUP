<?php
    require_once("../../api/api.php");
    require_once API::entity('comment');

    $commentid = $_GET['commentid'];
    $content = $_POST['content'];
    
    Comment::update($commentid, $content);

    header("Location: " . $_SERVER['HTTP_REFERER']);
?>