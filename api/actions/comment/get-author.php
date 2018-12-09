<?php
$auth = Auth::demandLevel('free');

$authorid = $args['authorid'];

if (!User::read($authorid)) {
    HTTPResponse::notFound("User with id $authorid");
}

$comments = Comment::getUser($authorid);

HTTPResponse::ok("Comments of user $authorid", $comments);
?>
