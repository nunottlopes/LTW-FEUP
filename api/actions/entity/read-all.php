<?php
$action = 'read-all';

$auth = Auth::demandLevel('free');

$entities = Entity::readAll();

HTTPResponse::ok("All entities", $entities);
?>
