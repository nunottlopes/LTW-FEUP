<?php
$action = 'read';

$auth = Auth::demandLevel('free');

$entityid = $args['entityid'];

$entity = Entity::read($entityid);

if (!$entity) {
    HTTPResponse::notFound("Entity with id $entityid");
}

HTTPResponse::ok("Entity $entityid", $entity);
?>
