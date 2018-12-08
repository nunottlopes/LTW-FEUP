<?php
if (API::gotargs('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $userid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $userid = $auth['userid'];
}

$comments = Save::getUserComments($userid);

HTTPResponse::ok("All comment saves of user $userid", $comments);
?>
