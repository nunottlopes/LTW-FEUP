<?php
$action = 'look';

$user = Auth::demandLevel('free');

$actions = [
    'create',
    'delete',
    'get-channel-user',
    'get-channel',
    'get-user',
    'read-all',
    'read',
    'update'
];

$data = [
    'actions' => $actions
];

HTTPResponse::look("Resource [story]", $data);
?>
