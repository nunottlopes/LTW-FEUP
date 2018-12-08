<?php
if (API::gotargs('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $userid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $userid = $auth['userid'];
}

$userid = $auth['userid'];

$stories = Save::getUserStories($userid);

HTTPResponse::ok("All story saves of user $userid", $stories);
?>
