<?php
require_once __DIR__ . '/../utils/http.php';
require_once __DIR__ . '/../entities/user.php';

// Require GET or HEAD
HTTPRequest::assertMethod(['GET', 'HEAD']);

Auth::demandLevel('auth');

// Data
$userid = $_SESSION['userid'];

$user = User::read((int)$userid);

if ($user) {
    HTTPResponse::ok($user);
} else {
    HTTPResponse::serverError();
}
?>
