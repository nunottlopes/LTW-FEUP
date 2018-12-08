<?php
require_once __DIR__ . '/../utils/http.php';
require_once __DIR__ . '/../entities/comment.php';

// Require GET or HEAD
HTTPRequest::assertMethod(['GET', 'HEAD']);

// Parse GET parameters.
$data = HTTPRequest::parse(['commentid']);

// Anyone can read comments.
Auth::demandLevel('free');

// Data
$commentid = $data['commentid'];

$comment = Comment::read((int)$commentid);

if ($comment) {
    HTTPResponse::ok($comment);
} else {
    HTTPResponse::notFound("Comment with id $commentid");
}
?>
