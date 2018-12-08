<?php
require_once API::entity('entity');

$action = 'get-entity';

$auth = Auth::demandLevel('admin');

$entityid = $args['entityid'];

if (!Entity::read($entityid)) {
    HTTPResponse::notFound("Entity with id $entityid");
}

$saves = Save::read($entityid);

HTTPResponse::ok("All saves of entity $entityid", $saves);
?>
