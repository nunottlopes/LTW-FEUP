<?php
$action = 'read';

$user = Auth::demandLevel('free');

$username = $args['username'];

$user = User::getByUsername($username);

if (!$user) {
    HTTPResponse::notFound("User with username $username");
}

HTTPResponse::ok("User $username", $user);
?>
