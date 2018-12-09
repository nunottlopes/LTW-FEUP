<?php
$userid = $auth['userid'];

if (!User::read($userid)) {
    HTTPRequest::notFound("User with id $userid");
}

$auth = Auth::demandLevel('authid', $userid);

$entityid = $args['entityid'];

if (!Entity::read($entityid)) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$body = HTTPRequest::parseBody(['vote']);

if ($body['vote'] === '+' || $body['vote'] === 'upvote') {
    $voted = 'Upvoted';
    $count = Vote::upvote($entityid, $userid);
} else if ($body['vote'] === '-' || $body['vote'] === 'downvote') {
    $voted = 'Downvoted';
    $count = Vote::downvote($entityid, $userid);
} else {
    HTTPResponse::invalid('vote', 'upvote or downvote');
}

$data = [
    'count' => $count,
    'entityid' => $entityid,
    'userid' => $userid
];

HTTPResponse::created("$voted entity $entityid", $data);
?>
