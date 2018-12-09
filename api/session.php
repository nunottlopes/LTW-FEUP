<?php
session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);

session_start();

if (!isset($_SESSION['authkey'])) {
    $_SESSION['authkey'] = bin2hex(openssl_random_pseudo_bytes(32));
    $_SESSION['authkey_timestamp'] = time();
}
?>
