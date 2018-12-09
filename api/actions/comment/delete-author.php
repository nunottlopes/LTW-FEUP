<?php
$authorid = $args['authorid'];

if (!User::read($authorid)) {
    HTTPResponse::notFound("User with id $authorid");
}

$auth = Auth::demandLevel('authid', $authorid);

$count = Comment::deleteUser($authorid);

$data = [
    'count' => $count
];

HTTPResponse::deleted("Deleted comments by $authorid", $data);
?>
