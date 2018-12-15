<?php
//session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);
session_start();

function restart_user_session() {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['CSRFTOKEN'] = bin2hex(openssl_random_pseudo_bytes(32));
}

const WAIT_EXPIRE_TIME = 1800; // 30 minutes
const INITIAL_EXPIRE_TIME = 1800; // 30 minutes

/**
 * No CSRF token?
 */
if (!isset($_SESSION['CSRFTOKEN'])) {
    restart_user_session();
}

/**
 * Session expired?
 */
if (isset($_SESSION['ACTIVITY_TIMESTAMP'])
    && (time() - $_SESSION['ACTIVITY_TIMESTAMP']) > WAIT_EXPIRE_TIME) {
    restart_user_session();
}

$_SESSION['ACTIVITY_TIMESTAMP'] = time();
?>
