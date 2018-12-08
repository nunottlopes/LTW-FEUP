<?php
$action = 'read-all';

$auth = Auth::demandLevel('free');

$users = User::readAll();

HTTPResponse::ok("All users", $users);
?>
