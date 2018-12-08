<?php
$action = 'delete-self';

$auth = Auth::demandLevel('auth');

$userid = $auth['userid'];

User::delete($userid);

Auth::logout();

HTTPResponse::deleted("Successfully deleted user account");
?>
