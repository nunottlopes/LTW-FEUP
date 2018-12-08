<?php
$action = 'look';

$auth = Auth::demandLevel('free');

$actions = [
    'create'           => ['channelid', 'storyTitle', 'storyType', 'content'],
    'delete'           => ['storyid', 'confirm-delete'],
    'get-channel-user' => ['authorid', 'channelid'],
    'get-channel'      => ['channelid'],
    'get-user'         => ['authorid'],
    'read-all'         => ['all'],
    'read'             => ['storyid'],
    'update'           => ['storyid', 'content']
];

$data = [
    'actions' => $actions
];

HTTPResponse::look("Resource [story]", $data);
?>
