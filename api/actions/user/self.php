<?php
$action = 'self';

$auth = Auth::demandLevel('auth');

$userid = $auth['userid'];

$user = User::self($userid);

HTTPResponse::ok("Self information $userid", $user);
?>
