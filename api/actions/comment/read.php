<?php
$action = 'read';

$auth = Auth::demandLevel('free');

$commentid = $args['commentid'];

$comment = Comment::read($commentid);

if (!$comment) {
    HTTPResponse::notFound("Comment with id $commentid");
}

HTTPResponse::ok("Comment $commentid", $comment);
?>
