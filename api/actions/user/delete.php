<?php
$action = 'delete';

$auth = Auth::demandLevel('admin');

$userid = (int)$args['userid'];

User::delete($userid);

HTTPResponse::deleted("Successfully deleted user account $userid");
?>
