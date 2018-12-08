<?php
$action = 'get-user';

$userid = $args['userid'];

if (!User::read($userid)) {
    HTTPResponse::notFound("User with id $userid");
}

$auth = Auth::demandLevel('authid', $userid);

$votes = Vote::getUser($userid);

HTTPResponse::ok("User $userid votes", $votes);
?>
