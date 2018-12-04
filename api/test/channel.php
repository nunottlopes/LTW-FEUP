<?php

require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/channel.php';

// Channel::readAll()
tprint(Channel::readAll(), 'readAll()');

brk();

// Channel::read($id)
tprint(Channel::read(1), 'read(1)');
tprint(Channel::read(2), 'read(2)');
tprint(Channel::read(3), 'read(3)');

brk();

// Channel::get($name)
tprint(Channel::get('showerthoughts'), 'get(showerthoughts)');
tprint(Channel::get('askscience'), 'get(askscience)');

brk();

// Channel::create($name, $creator)
tprint(Channel::create('news', 4), 'create(news, 4)');
tprint(Channel::create('pics', 2), 'create(pics, 2)');
tprint(Channel::create('politics', 5), 'create(politics, 5)');

tprint(Channel::get('news'), 'get(news)');
tprint(Channel::get('pics'), 'get(pics)');
tprint(Channel::get('politics'), 'get(politics)');

brk();

?>