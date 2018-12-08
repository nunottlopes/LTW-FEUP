<?php
$action = 'update';

$commentid = (int)$args['commentid'];
$content = $args['content'];

$comment = Comment::read($commentid);

if ($comment) {
    $authorid = $comment['authorid'];

    $auth = Auth::demandLevel('authid', $authorid);

    $result = Comment::update($commentid, $content);

    if ($result) {
        HTTPResponse::updated("Comment successfully updated");
    }
    
    HTTPResponse::serverError();
}

HTTPResponse::notFound("Comment with id $commentid");
?>
