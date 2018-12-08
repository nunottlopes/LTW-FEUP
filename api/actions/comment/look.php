<?php
$action = 'look';

$auth = Auth::demandLevel('free');

$actions = [
    'create'            => ['parentid', 'content'],
    'delete'            => ['commentid', 'confirm-delete'],
    'get-children-user' => ['authorid', 'parentid'],
    'get-children'      => ['parentid'],
    'get-user'          => ['authorid'],
    'read-all'          => ['all'],
    'read'              => ['commentid'],
    'update'            => ['commentid', 'content']
];

$data = [
    'actions' => $actions
];

HTTPResponse::look("Resource [comment]", $data);
?>
