<?php

require_once __DIR__ . '/test.php';
require_once __DIR__ . '/../entities/user.php';

// User::readAll
tprint(User::readAll(), 'readAll()');

brk();

// User::read
tprint(User::read(1), 'read(1)');
tprint(User::read(2), 'read(2)');
tprint(User::read(3), 'read(3)');

brk();

// User::get
tprint(User::get('Amadeu'), 'get(Amadeu)');
tprint(User::get('Sofia'), 'get(Sofia)');
tprint(User::get('david.andrade@gmail.com'), 'get(david.andrade@gmail.com)');

brk();

// User::authenticate
tprint(User::authenticate('Amadeu', 'amadeu'), 'authenticate(Amadeu, amadeu)');
tprint(User::authenticate('Amadeu', '123456'), 'authenticate(Amadeu, 123456)');
tprint(User::authenticate('David', 'qwerty'), 'authenticate(David, qwerty)');
tprint(User::authenticate('Emanuel', '123456'), 'authenticate(Emanuel, 123456)');
tprint(User::authenticate('Tiago', '12121212'), 'authenticate(Tiago, 123456)');
tprint(User::authenticate('Sofia', '12121212'), 'authenticate(Sofia, 12121212)');
tprint(User::authenticate('nuno.lopes@gmail.com', '123456'), 'authenticate(nuno.lopes@gmail.com, 123456)');
tprint(User::authenticate('nuno.lopes@gmail.com', 'nuno'), 'authenticate(nuno.lopes@gmail.com, nuno)');

brk();

// User::create
if (isset($_GET['create'])) {
    hdr('create(Carlos, carlos.sousa@gmail.com, carlitos)');
    test([User::class, 'create'], ['Carlos', 'carlos.sousa@gmail.com', 'carlitos']);
    
    hdr('create(henrique123, henrique123@gmail.com, gengibre)');
    test([User::class, 'create'], ['henrique123', 'henrique123@gmail.com', 'gengibre']);

    hdr('create(send_nudes, nudes@send.nudes, qweasdzxc123)');
    test([User::class, 'create'], ['send_nudes', 'nudes@send.nudes', 'qweasdzxc123']);

    hdr('create(invalid@username, valid@gmail.com, 12341234)');
    test([User::class, 'create'], ['invalid@username', 'valid@gmail.com', '12341234']);

    hdr('create(valid+username, invalid@#@gmail.com, 12341234)');
    test([User::class, 'create'], ['valid+username', 'invalid@#@gmail.com', '12341234']);

    hdr('authenticate(Carlos, carlitos)');
    tprint(User::authenticate('Carlos', 'carlitos'));

    hdr('authenticate(henrique123, 123)');
    tprint(User::authenticate('henrique123', '123'));

    tprint(User::readAll(), 'readAll()');
    brk();
}

// User::delete
if (isset($_GET['delete'])) {    
    hdr('delete(8)');
    test([User::class, 'delete'], [4]);

    hdr('delete(12)');
    test([User::class, 'delete'], [12]);

    hdr('delete(15)');
    test([User::class, 'delete'], [15]);

    tprint(User::readAll(), 'readAll()');
    brk();
}

?>