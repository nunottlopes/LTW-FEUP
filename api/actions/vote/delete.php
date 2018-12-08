<?php
$action = 'delete';

if (got('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $userid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $userid = $auth['userid'];
}

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
