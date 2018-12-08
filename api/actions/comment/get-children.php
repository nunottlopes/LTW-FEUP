<?php
$auth = Auth::demandLevel('free');

$parentid = $args['parentid'];

if (!Entity::read($parentid)) {
    HTTPResponse::adjacentNotFound("Parent Entity with id $parentid");
}

$comments = Comment::getChildren($parentid);

HTTPResponse::ok("Comments of entity $parentid", $comments);
?>
