<?php
$admin = Auth::level('admin');

if (!$admin) {
    HTTPResponse::unauthorized(0);
}

$auth = Auth::demandLevel('admin'); // populate $auth

HTTPResponse::ok("Authenticated as administrator", []);
?>
