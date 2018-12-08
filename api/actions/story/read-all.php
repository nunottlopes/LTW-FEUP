<?php
$action = 'read-all';

$auth = Auth::demandLevel('free');

$stories = Story::readAll();

HTTPResponse::ok("All stories", $stories);
?>
