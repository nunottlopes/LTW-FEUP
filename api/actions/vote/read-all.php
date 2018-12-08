<?php
$auth = Auth::demandLevel('admin');

$votes = Vote::readAll();

HTTPResponse::ok("All votes", $votes);
?>
