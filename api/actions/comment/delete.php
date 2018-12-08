<?php
$commentid = $args['commentid'];

$comment = Comment::read($commentid);

if (!$comment) {
    HTTPResponse::notFound("Comment with id $commentid");
}

$authorid = $comment['authorid'];

$auth = Auth::demandLevel('authid', $authorid);

$count = Comment::delete($commentid);

$data = [
    'count' => $count,
    'comment' => $comment
];

HTTPResponse::deleted("Deleted comment $commentid", $data);
?>
