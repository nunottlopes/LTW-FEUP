<?php
//session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);
session_start();

function newCSRF() {
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
    $_SESSION['csrf_timestamp'] = time();
}

if (!isset($_SESSION['csrf'])) newCSRF();
?>
