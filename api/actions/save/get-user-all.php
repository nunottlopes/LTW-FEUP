<?php
$userid = $auth['userid'];

if (!User::read($userid)) {
    HTTPRequest::notFound("User with id $userid");
}

$auth = Auth::demandLevel('authid', $userid);

$saves = Save::getUser($userid);

HTTPResponse::ok("All entity saves of user $userid", $saves);
?>
