<?php
$useremail = $args['email'];

$user = User::getByEmail($useremail);

if (!$user) {
    HTTPResponse::notFound("User $useremail");
}

$userid = $user['userid'];

$auth = Auth::demandLevel('authid', $userid);

if ($userid === 0) {
    HTTPResponse::badRequest("Cannot delete admin account");
}

$count = User::delete($userid);

if (!$auth['admin']) Auth::logout();

$data = [
    'count' => $count,
    'user' => $user
];

HTTPResponse::deleted("Successfully deleted user account");
?>
