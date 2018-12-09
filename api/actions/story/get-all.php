<?php
$auth = Auth::demandLevel('free');

$stories = Story::readAll();

HTTPResponse::ok("All stories", $stories);
?>
