<?php
$action = 'self';

$auth = Auth::demandLevel('user');

$userid = (int)$auth['userid'];

$user = User::self($userid);

HTTPResponse::ok("Self information $userid", $user);
?>
