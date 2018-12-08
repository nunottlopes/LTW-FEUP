<?php
$action = 'read';

$auth = Auth::demandLevel('free');

$users = User::readAll();

HTTPResponse::ok("All users", $users);
?>
