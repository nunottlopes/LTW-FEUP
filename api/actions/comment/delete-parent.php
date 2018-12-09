<?php
$auth = Auth::demandLevel('admin');

$parentid = $args['parentid'];

if (!Entity::read($parentid)) {
    HTTPRequest::notFound("Parent Entity with id $entityid");
}

$count = Comment::deleteChildren($parentid);

$data = [
    'count' => $count
];

HTTPResponse::deleted("Deleted children of $parentid", $data);
?>
