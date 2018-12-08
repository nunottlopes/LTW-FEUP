<?php
require_once __DIR__ . '/../utils/http.php';
require_once __DIR__ . '/../entities/tree.php';

// Require GET or HEAD
HTTPRequest::assertMethod(['GET', 'HEAD']);

// Parse GET parameters.
$data = HTTPRequest::parse(['parentid']);

// Anyone can read comment trees.
Auth::demandLevel('free');

// Data
$parentid = $data['parentid'];

$tree = Tree::getTree((int)$parentid);

if ($tree) {
    HTTPResponse::ok($tree);
} else {
    HTTPResponse::notFound("Entity with id $parentid");
}
?>
