<?php
require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/comment.php';

// Comment::readAll
tprint(Comment::readAll(), 'readAll()');

brk();

// Comment::read
tprint(Comment::read(7), 'read(7)');
tprint(Comment::read(8), 'read(8)');
tprint(Comment::read(9), 'read(9)');

brk();

// Comment::getUser
tprint(Comment::getUser(5), 'getUser(5)');
tprint(Comment::getUser(1), 'getUser(1)');

brk();

// Comment::getChildren
tprint(Comment::getChildren(7), 'getChildren(7)');
tprint(Comment::getChildren(8), 'getChildren(8)');

brk();

// Comment::getChildrenUser
tprint(Comment::getChildrenUser(3, 5), 'getChildrenUser(3, 5)');
tprint(Comment::getChildrenUser(8, 1), 'getChildrenUser(8, 1)');

brk();

// Comment::create
if (isset($_GET['create'])) {
    hdr('create(7, 1, "I say 13 again")');
    test([Comment::class, 'create'], [7, 1, 'I say 13 again']);

    hdr('create(12, 2, "I say 11 again")');
    test([Comment::class, 'create'], [12, 2, 'I say 11 again']);

    hdr('create(3, 5, "You are epilectic you fuck")');
    test([Comment::class, 'create'], [3, 5, 'You are epilectic you fuck']);

    tprint(Comment::readAll(), 'readAll()');
    brk();
}

// Comment::delete
if (isset($_GET['delete'])) {
    hdr('delete(10)');
    test([Comment::class, 'delete'], [10]);

    hdr('delete(7)');
    test([Comment::class, 'delete'], [7]);

    hdr('delete(17)');
    test([Comment::class, 'delete'], [17]);

    tprint(Comment::readAll(), 'readAll()');
    brk();
}

// Comment::update
if (isset($_GET['update'])) {
    hdr('update(14, "Research does agree")');
    test([Comment::class, 'update'], [14, 'Research does agree']);

    //hdr('update(13, "Now it''s 2")');
    //test([Comment::class, 'update'], [13, 'Now it''s 2']);

    hdr('update(9, "Wak wat?")');
    test([Comment::class, 'update'], [9, 'Wak wat?']);

    hdr('clear(11)');
    test([Comment::class, 'clear'], [11]);

    tprint(Comment::readAll(), 'readAll()');
    brk();
}

?>