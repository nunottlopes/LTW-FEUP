<?php
$action = 'look';

$auth = Auth::demandLevel('free');

HTTPResponse::look("Resource [user]");
?>
