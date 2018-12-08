<?php
$action = 'get-by-email';

$user = Auth::demandLevel('free');

$useremail = $args['email'];

$user = User::getByEmail($useremail);

if (!$user) {
    HTTPResponse::notFound("User with email $useremail");
}

HTTPResponse::ok("User $useremail", $user);
?>
