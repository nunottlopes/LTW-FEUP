<?php
require_once __DIR__ . '/api.php';

$resource = 'query';

$methods = ['GET', 'HEAD'];

$parameters = ['method', 'resource'];

$actions = [
    'call'   => ['GET', 'method', 'resource'],
    'look'   => ['GET']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        $action = 'look';
        $auth = Auth::demandLevel('free');
        HTTPResponse::look("Utility [query]");
    }
    if (gotargs('method', 'resource')) {
        HTTPRequest::$methodBackdoor = $args['method'];
        require_once API::resource($args['resource']);
    }
    break;
}

HTTPResponse::noAction();
?>
