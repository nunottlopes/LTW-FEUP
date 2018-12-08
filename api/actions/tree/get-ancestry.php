<?php
$auth = Auth::demandLevel('free');

$childid = $args['childid'];

if (!Entity::read($childid)) {
    HTTPResponse::notFound("Child Entity with id $childid");
}

$ancestry = Tree::getAscendants($childid);

HTTPResponse::ok("Ancestry of $childid", $ancestry);
?>
