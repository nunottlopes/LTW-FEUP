<?php
require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/channel.php';

// Channel::readAll
tprint(Channel::readAll(), 'readAll()');

brk();

// Channel::read
tprint(Channel::read(1), 'read(1)');
tprint(Channel::read(2), 'read(2)');
tprint(Channel::read(3), 'read(3)');

brk();

// Channel::get
tprint(Channel::get('showerthoughts'), 'get(showerthoughts)');
tprint(Channel::get('askscience'), 'get(askscience)');

brk();

// Channel::create
if (isset($_GET['create'])) {
    hdr('create(news, 4)');
    test([Channel::class, 'create'], ['news', 4]);

    hdr('create(pics, 5)');
    test([Channel::class, 'create'], ['pics', 5]);
    
    hdr('create(politics, 73)');
    test([Channel::class, 'create'], ['politics', 73]);

    tprint(Channel::readAll(), 'readAll()');
    brk();
}

// Channel::delete
if (isset($_GET['delete'])) {
    hdr('delete(4)');
    test([Channel::class, 'delete'], [4]);

    hdr('delete(5)');
    test([Channel::class, 'delete'], [5]);

    hdr('delete(6)');
    test([Channel::class, 'delete'], [6]);

    tprint(Channel::readAll(), 'readAll()');
    brk();
}

?>