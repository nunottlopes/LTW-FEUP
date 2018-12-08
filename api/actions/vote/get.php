<?php
$action = 'get';

$userid = $args['userid'];

if (!User::read($userid)) {
    HTTPResponse::notFound("User with id $userid");
}

$auth = Auth::demandLevel('authid', $userid);

$entityid = $args['entityid'];

if (!Entity::read($entityid)) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$vote = Vote::get($entityid, $userid);

HTTPResponse::ok("User $userid vote for entity $entityid", $vote);
?>
