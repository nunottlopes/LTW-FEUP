<?php
$commentid = $args['commentid'];
$content = $args['content'];

$comment = Comment::read($commentid);

if (!$comment) {
    HTTPResponse::notFound("Comment with id $commentid");
}

$authorid = $comment['authorid'];

$auth = Auth::demandLevel('authid', $authorid);

$count = Comment::update($commentid, $content);

$data = [
    'count' => $count,
    'previous' => $comment
];

HTTPResponse::updated("Comment successfully updated", $data);
?>
