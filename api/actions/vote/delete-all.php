<?php
$auth = Auth::demandLevel('admin');

$entityid = $args['entityid'];

if (!Entity::read($entityid)) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$count = Vote::deleteEntity($entityid);

$data = [
    'count' => $count,
    'entityid' => $entityid
];

HTTPResponse::deleted("Deleted all votes for entity $entityid", $data);
?>
