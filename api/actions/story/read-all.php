<?php
$action = 'read-all';

$user = Auth::demandLevel('free');

$stories = Story::readAll();

HTTPResponse::ok("All stories", $stories);
?>
