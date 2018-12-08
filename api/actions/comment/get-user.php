<?php
$action = 'get-user';

$auth = Auth::demandLevel('free');

$authorid = (int)$args['authorid'];

if (!User::read($authorid)) {
    HTTPResponse::adjacentNotFound("User with id $authorid");
}

$comments = Comment::getUser($authorid);

HTTPResponse::ok("Comments of user $authorid", $comments);
?>
