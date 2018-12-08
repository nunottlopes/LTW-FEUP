<?php
session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);

session_start();

if (!isset($_SESSION['authkey'])) {
    $_SESSION['authkey'] = bin2hex(openssl_random_pseudo_bytes(32));
    $_SESSION['authkey_timestamp'] = time();
}

function session_reset() {
    session_destroy();
    session_start();

    if (isset($_SESSION['userid'])) unset($_SESSION['userid']);
    if (isset($_SESSION['username'])) unset($_SESSION['username']);
    if (isset($_SESSION['useremail'])) unset($_SESSION['useremail']);
    if (isset($_SESSION['login_timestamp'])) unset($_SESSION['login_timestamp']);
    if (isset($_SESSION['authkey'])) unset($_SESSION['authkey']);
    if (isset($_SESSION['authkey_timestamp'])) unset($_SESSION['authkey_timestamp']);

    return true;
}
?>
