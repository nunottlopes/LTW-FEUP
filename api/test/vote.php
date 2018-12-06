<?php
require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/vote.php';

// Vote::readAll
tprint(Vote::readAll(), 'readAll()');

brk();

// Vote::get
tprint(Vote::get(1, 6), 'get(1, 6)');
tprint(Vote::get(2, 1), 'get(2, 1)');
tprint(Vote::get(3, 1), 'get(3, 1)');
tprint(Vote::get(4, 1), 'get(4, 1)');

brk();

// Vote::create
if (isset($_GET['create'])) {
    tprint(Vote::getEntity(1), 'getEntity(1)');
    
    hdr('upvote(1, 4)');
    test([Vote::class, 'upvote'], [1, 4]);
    tprint(Vote::getEntity(1), 'getEntity(1)');

    hdr('downvote(1, 6)');
    test([Vote::class, 'downvote'], [1, 6]);
    tprint(Vote::getEntity(1), 'getEntity(1)');

    hdr('upvote(19, 2)');
    test([Vote::class, 'upvote'], [19, 2]);
    tprint(Vote::getEntity(1), 'getEntity(1)');

    tprint(Vote::readAll(), 'readAll()');
    brk();
}

// Vote::delete
if (isset($_GET['delete'])) {
    hdr('delete(1, 4)');
    test([Vote::class, 'delete'], [1, 4]);

    hdr('delete(1, 2)');
    test([Vote::class, 'delete'], [1, 2]);

    tprint(Vote::readAll(), 'readAll()');
    brk();
}

?>