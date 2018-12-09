<?php
$userid = $auth['userid'];

if (!User::read($userid)) {
    HTTPRequest::notFound("User with id $userid");
}

$auth = Auth::demandLevel('authid', $userid);

$userid = $auth['userid'];

$stories = Save::getUserStories($userid);

HTTPResponse::ok("All story saves of user $userid", $stories);
?>
