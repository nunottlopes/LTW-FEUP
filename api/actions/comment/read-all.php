<?php
$auth = Auth::demandLevel('free');

$comments = Comment::readAll();

HTTPResponse::ok("All comments", $comments);
?>
