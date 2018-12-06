<?php
require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/story.php';

// Story::readAll
tprint(Story::readAll(), 'readAll()');

brk();

// Story::read
tprint(Story::read(1), 'read(1)');
tprint(Story::read(2), 'read(2)');
tprint(Story::read(3), 'read(3)');

brk();

// Story::getChannel
tprint(Story::getChannel(3), 'getChannel(3)');
tprint(Story::getChannel(2), 'getChannel(2)');

brk();

// Story::getUser
tprint(Story::getUser(6), 'getUser(6)');
tprint(Story::getUser(3), 'getUser(3)');

brk();

// Story::create
if (isset($_GET['create'])) {
    hdr('create(1, 3, "DJ Miguel", title, "")');
    test([Story::class, 'create'], [1, 3, 'DJ Miguel', 'title', '']);

    hdr('create(1, 5, "DJ Nuno Ramos", self, "Toma la com o chicote")');
    test([Story::class, 'create'], [1, 5, 'DJ Nuno Ramos', 'self', 'Toma la com o chicote']);

    hdr('create(4, 3, "Titulo aleatorio", title, "")');
    test([Story::class, 'create'], [4, 3, 'Titulo aleatorio', 'title', '']);

    tprint(Story::readAll(), 'readAll()');
    brk();
}

// Story::delete
if (isset($_GET['delete'])) {
    hdr('delete(1)');
    test([Story::class, 'delete'], [1]);

    hdr('delete(4)');
    test([Story::class, 'delete'], [4]);

    hdr('delete(6)');
    test([Story::class, 'delete'], [6]);

    tprint(Story::readAll(), 'readAll()');
    brk();
}

// Story::update
if (isset($_GET['update'])) {
    hdr('update(2, "Conteudo atualizado")');
    test([Story::class, 'update'], [2, 'Conteudo atualizado']);

    hdr('update(4, "but but but")');
    test([Story::class, 'update'], [4, 'but but but']);

    hdr('update(5, "Once upon a time")');
    test([Story::class, 'update'], [5, 'Once upon a time']);

    tprint(Story::readAll(), 'readAll()');
    brk();
}

?>