<?php
require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/tree.php';

// Tree::getTree
hdr('getTree(1)');
test([Tree::class, 'getTree'], [1]);

// Tree::getAscendants
hdr('getAscendants(16)');
test([Tree::class, 'getAscendants'], [16]);

brk();
?>