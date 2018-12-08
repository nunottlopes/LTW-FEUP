<?php
$action = 'delete';

if (got('userid')) {
    $auth = Auth::demandLevel('authid', $args['userid']);
    $userid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $userid = $auth['userid'];
}

$user = User::read($userid);

if (!$user) {
    HTTPResponse::notFound("User with id $userid");
}

$count = User::delete($userid);

if (!Auth::admin()) Auth::logout();

$data = [
    'count' => $count,
    'user' => $user
];

HTTPResponse::deleted("Successfully deleted user account");
?>
