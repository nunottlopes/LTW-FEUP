<?php
$action = 'get-tree';

$auth = Auth::demandLevel('free');

$parentid = $args['parentid'];

if (!Entity::read($parentid)) {
    HTTPResponse::notFound("Parent Entity with id $entityid");
}

$tree = Tree::getTree($parentid);

HTTPResponse::ok("Comment tree on $parentid", $tree);
?>
