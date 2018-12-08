<?php
$action = 'upvote';

if (got('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $userid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $userid = $auth['userid'];
}

$entityid = $args['entityid'];

$entity = Entity::read($entityid);

if (!$entity) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$count = Vote::upvote($entityid, $userid);

$data = [
    'count' => $count,
    'userid' => $userid,
    'entityid' => $entityid,
    'entity' => $entity,
    'vote' => '+'
];

HTTPResponse::created("Upvoted entity $entityid by user $userid", $data);
?>
