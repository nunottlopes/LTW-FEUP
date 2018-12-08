<?php
$action = 'create';

if (got('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $authorid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $authorid = $auth['userid'];
}

$parentid = $args['parentid'];
$content = $args['content'];

if (!Entity::read($parentid)) {
    HTTPResponse::adjacentNotFound("Parent Entity with id $parentid");
}

$commentid = Comment::create($parentid, $authorid, $content);

if (!$commentid) {
    HTTPResponse::serverError();
}

$data = [
    'commentid' => $commentid,
    'entityid' => $commentid,
    'parentid' => $parentid,
    'authorid' => $authorid,
    'content' => $content
];

HTTPResponse::created("Created comment $commentid, child of $parentid", $data);
?>
