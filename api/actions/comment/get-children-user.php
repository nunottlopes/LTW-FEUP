<?php
$auth = Auth::demandLevel('free');

$authorid = $args['authorid'];
$parentid = $args['parentid'];

if (!User::read($authorid)) {
    HTTPResponse::adjacentNotFound("User with id $authorid");
}

if (!Entity::read($parentid)) {
    HTTPResponse::adjacentNotFound("Parent Entity with id $parentid");
}

$comments = Comment::getChildrenUser($parentid, $authorid);

HTTPResponse::ok("Comments of user $authorid for entity $parentid", $comments);
?>
