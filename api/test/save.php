<?php
require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/save.php';

// Save::readAll
tprint(Save::readAll(), 'readAll()');

brk();

// Save::getUserStories
tprint(Save::getUserStories(1), 'getUserStories(1)');
tprint(Save::getUserStories(3), 'getUserStories(3)');

brk();

// Save::getUserComments
tprint(Save::getUserComments(3), 'getUserComments(3)');

brk();

// Save::getUser
tprint(Save::getUser(3), 'getUser(3)');

brk();

// Save::getEntity
tprint(Save::getEntity(3), 'getEntity(3)');

brk();

// Save::create
if (isset($_GET['create'])) {
    hdr('create(1, 5)');
    test([Save::class, 'create'], [1, 5]);

    hdr('create(1, 6)');
    test([Save::class, 'create'], [1, 6]);

    hdr('create(10, 2)');
    test([Save::class, 'create'], [10, 2]);

    tprint(Save::readAll(), 'readAll()');
    brk();
}

// Save::delete
if (isset($_GET['delete'])) {
    hdr('delete(3, 1)');
    test([Save::class, 'delete'], [3, 1]);

    hdr('delete(1, 2)');
    test([Save::class, 'delete'], [1, 2]);

    tprint(Save::readAll(), 'readAll()');
    brk();
}

?>