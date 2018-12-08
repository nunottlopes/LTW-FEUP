<?php
$action = 'look';

$auth = Auth::demandLevel('free');

$actions = [
    'create'   => ['channelname'],
    'delete'   => ['channelid', 'confirm-delete'],
    'get'      => ['channelname'],
    'read-all' => ['all'],
    'read'     => ['channelid'],
    'valid'    => ['channelname', 'valid']
];

$data = [
    'actions' => $actions
];

HTTPResponse::look("Resource [channel]", $data);
?>
