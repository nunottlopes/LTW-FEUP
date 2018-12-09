<?php
$userid = $auth['userid'];

if (!User::read($userid)) {
    HTTPRequest::notFound("User with id $userid");
}

$auth = Auth::demandLevel('authid', $userid);

$count = Vote::deleteUser($userid);

$data = [
    'count' => $count,
    'userid' => $userid
];

HTTPResponse::deleted("Deleted all votes by user $userid", $data);
?>
