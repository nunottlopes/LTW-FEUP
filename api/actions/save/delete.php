<?php
if (API::gotargs('userid')) { // admin impersonation
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

$count = Save::delete($entityid, $userid);

$data = [
    'count' => $count,
    'entityid' => $entityid,
    'userid' => $userid
];

HTTPResponse::deleted("Deleted save of entity $entityid by user $userid", $data);
?>
