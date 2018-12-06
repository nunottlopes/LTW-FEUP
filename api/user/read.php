<?php
require_once __DIR__ . '/../entities/user.php';
require_once __DIR__ . '/../session/auth.php';

if (!isset($_GET['userid'])) {
    http_response_code(400);
}

?>
