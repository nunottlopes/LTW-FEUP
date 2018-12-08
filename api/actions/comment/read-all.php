<?php
$action = 'read-all';

$auth = Auth::demandLevel('free');

$comments = Comment::readAll();

HTTPResponse::ok("All comments", $comments);
?>
