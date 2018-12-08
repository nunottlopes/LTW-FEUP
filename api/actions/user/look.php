<?php
$action = 'look';

$auth = Auth::demandLevel('free');

$actions = [
    'create-self'     => ['username', 'email', 'password', 'confirm'],
    'delete-self'     => ['confirm-delete'],
    'get-by-username' => ['username'],
    'get-by-email'    => ['email'],
    'read-all'        => ['all'],
    'read'            => ['userid'],
    'self'            => ['self']
];

$data = [
    'actions' => $actions
];

HTTPResponse::look("Resource [user]", $data);
?>
