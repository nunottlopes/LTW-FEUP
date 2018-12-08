<?php
$action = 'get-entity';

$entityid = $args['entityid'];

if (!Entity::read($entityid)) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$auth = Auth::demandLevel('admin');

$votes = Vote::getEntity($entityid);

HTTPResponse::ok("Entity $entityid votes", $votes);
?>
