<?php
$auth = Auth::demandLevel('admin');

$saves = Save::readAll();

HTTPResponse::ok("All saves", $saves);
?>
