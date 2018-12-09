<?php
if (API::gotargs('userid')) {
    $auth = Auth::demandLevel('authid', $args['userid']);
    $userid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $userid = $auth['userid'];
}

if ($userid === 0) {
    HTTPResponse::badRequest("Cannot delete admin account");
}

$user = User::read($userid);

$count = User::delete($userid);

if (!$auth['admin']) Auth::logout();

$data = [
    'count' => $count,
    'user' => $user
];

HTTPResponse::deleted("Successfully deleted user account");
?>
