<?php
$action = 'admin';

$admin = Auth::level('admin');

if (!$admin) {
    HTTPResponse::unauthorized(0);
}

$auth = Auth::demandLevel('admin');

HTTPResponse::ok("Authenticated as administrator", []);
?>
