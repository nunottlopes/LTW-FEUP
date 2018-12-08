<?php
$action = 'read-all';

$auth = Auth::demandLevel('free');

$channels = Channel::readAll();

HTTPResponse::ok("All channels", $channels);
?>
