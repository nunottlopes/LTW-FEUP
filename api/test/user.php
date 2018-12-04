<?php

require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/user.php';

// User::readAll()
tprint(User::readAll(), 'readAll()');

brk();

// User::read($id)
tprint(User::read(1), 'read(1)');
tprint(User::read(2), 'read(2)');
tprint(User::read(3), 'read(3)');

brk();

// User::get($name)
tprint(User::get('Amadeu'), 'get(Amadeu)');
tprint(User::get('Sofia'), 'get(Sofia)');
tprint(User::get('david.andrade@gmail.com'), 'get(david.andrade@gmail.com)');

brk();

// User::authenticate($name, $password)
tprint(User::authenticate('Amadeu', 'amadeu'), 'authenticate(Amadeu, amadeu)');
tprint(User::authenticate('Amadeu', '123456'), 'authenticate(Amadeu, 123456)');
tprint(User::authenticate('David', 'qwerty'), 'authenticate(David, qwerty)');
tprint(User::authenticate('Emanuel', '123456'), 'authenticate(Emanuel, 123456)');
tprint(User::authenticate('Tiago', '12121212'), 'authenticate(Tiago, 123456)');
tprint(User::authenticate('Sofia', '12121212'), 'authenticate(Sofia, 12121212)');
tprint(User::authenticate('nuno.lopes@gmail.com', '123456'), 'authenticate(nuno.lopes@gmail.com, 123456)');
tprint(User::authenticate('nuno.lopes@gmail.com', 'nuno'), 'authenticate(nuno.lopes@gmail.com, nuno)');

brk();

// User::create($username, $email, $password)
tprint(User::create('Carlos', 'carlos.sousa@gmail.com', 'carlitos'),
    'create(Carlos, carlos.sousa@gmail.com, carlitos)');
tprint(User::create('henrique123', 'henrique123@gmail.com', 'gengibre'),
    'create(henrique123, henrique123@gmail.com, gengibre)');
tprint(User::create('send_nudes', 'nudes@send.nudes', 'qweasdzxc123'),
    'create(send_nudes, nudes@send.nudes, qweasdzxc123)');

tprint(User::get('Carlos'), 'get(Carlos)');
tprint(User::get('henrique123'), 'get(henrique123)');
tprint(User::get('nudes@send.nudes'), 'get(nudes@send.nudes)');

brk();

?>