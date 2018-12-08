<?php
$action = 'create';

$auth = Auth::demandLevel('auth');

$parentid = (int)$args['parentid'];
$content = $args['content'];
$authorid = $auth['userid'];

if (!Entity::read($parentid)) {
    HTTPResponse::adjacentNotFound("Entity with id $parentid");
}

$commentid = Comment::create($parentid, $authorid, $content);

if ($commentid) {
    $data = [
        'commentid' => $commentid,
        'entityid' => $commentid,
        'parentid' => $parentid
    ];
    HTTPResponse::created("Created comment $commentid, child of $parentid", $data);
}

HTTPResponse::serverError();
?>
