<?php
$action = 'get-user-entities';

if (got('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $userid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $userid = $auth['userid'];
}

$saves = Save::getUser($userid);

HTTPResponse::ok("All entity saves of user $userid", $saves);
?>
