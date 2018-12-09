<?php
$authorid = $auth['authorid'];

if (!User::read($authorid)) {
    HTTPRequest::notFound("User with id $authorid");
}

$auth = Auth::demandLevel('authid', $authorid);

$parentid = $args['parentid'];

if (!Entity::read($parentid)) {
    HTTPResponse::notFound("Parent Entity with id $parentid");
}

$content = HTTPRequest::getContent();

$commentid = Comment::create($parentid, $authorid, $content);

if (!$commentid) {
    HTTPResponse::serverError();
}

$comment = Comment::read($commentid);

$data = [
    'commentid' => $commentid,
    'comment' => $comment
];

HTTPResponse::created("Created comment $commentid, child of $parentid", $data);
?>
