<?php
$action = 'delete';

$commentid = (int)$args['commentid'];

$comment = Comment::read($commentid);

if ($comment) {
    $authorid = (int)$comment['authorid'];

    $auth = Auth::demandLevel('authid', $authorid);

    Comment::delete($commentid);

    HTTPResponse::deleted("Deleted comment $commentid");
}

HTTPResponse::notFound("Comment with id $commentid");
?>
