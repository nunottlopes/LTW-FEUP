<?php
$auth = Auth::demandLevel('admin');

$entityid = $args['entityid'];

if (!Entity::read($entityid)) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$votes = Vote::getEntity($entityid);

HTTPResponse::ok("Entity $entityid votes", $votes);
?>
