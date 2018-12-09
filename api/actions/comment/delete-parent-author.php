<?php
$auth = Auth::demandLevel('admin');

$parentid = $args['parentid'];
$authorid = $args['authorid'];

if (!Entity::read($parentid)) {
    HTTPRequest::notFound("Parent Entity with id $entityid");
}

if (!User::read($authorid)) {
    HTTPRequest::notFound("User with id $authorid");
}

$count = Comment::deleteChildrenUser($parentid, $authorid);

$data = [
    'count' => $count
];

HTTPResponse::deleted("Deleted children of $parentid by $authorid", $data);
?>
