<?php
$action = 'read-all';

$auth = Auth::demandLevel('admin');

$votes = Vote::readAll();

HTTPResponse::ok("All votes", $votes);
?>
