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

$count = Vote::delete($entityid, $userid);

$data = [
    'count' => $count,
    'entityid' => $entityid,
    'userid' => $userid
];

HTTPResponse::deleted("Deleted vote for entity $entityid by user $userid", $data);
?>
