<?php
$commentid = $args['commentid'];

$comment = Comment::read($commentid);

if (!$comment) {
    HTTPResponse::notFound("Comment with id $commentid");
}

$authorid = $comment['authorid'];

$auth = Auth::demandLevel('authid', $authorid);

$content = HTTPRequest::getContent();

$count = Comment::update($commentid, $content);

$new = Comment::read($commentid);

$data = [
    'count' => $count,
    'old' => $comment,
    'new' => $new
];

HTTPResponse::updated("Comment $commentid successfully edited", $data);
?>
