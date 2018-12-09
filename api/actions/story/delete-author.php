<?php
$authorid = $auth['authorid'];

if (!User::read($authorid)) {
    HTTPRequest::notFound("User with id $authorid");
}

$auth = Auth::demandLevel('authid', $authorid);

$count = Story::deleteUser($authorid);

$data = [
    'count' => $count
];

HTTPResponse::deleted("Deleted all stories by $authorid", $data);
?>
