<?php
$auth = Auth::demandLevel('admin');

$entityid = $args['entityid'];

if (!Entity::read($entityid)) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$count = Save::deleteEntity($entityid);

$data = [
    'count' => $count,
    'entityid' => $entityid
];

HTTPResponse::deleted("Deleted all saves of entity $entityid", $data);
?>
