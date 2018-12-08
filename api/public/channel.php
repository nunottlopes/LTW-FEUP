<?php
require_once __DIR__ . '/../utils/http.php';
require_once API::entity('channel');

$resource = 'channel';

$supported = ['GET', 'HEAD', 'POST', 'DELETE'];

$parameters = ['channelid', 'channelname', 'valid', 'confirm-delete', 'all'];

$method = HTTPRequest::method($supported, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (got('channelid')) {
        API::action('read');
    }
    if (got('all')) {
        API::action('read-all');
    }
    if (got('channelname') && got('valid')) {
        API::action('valid');
    }
    if (got('channelname')) {
        API::action('get');
    }
    break;
case 'POST':
    if (got('channelname')) {
        API::action('create');
    }
    break;
case 'DELETE':
    if (got('channelid') && got('confirm-delete')) {
        API::action('delete');
    }
    break;
}

HTTPResponse::noAction($method);
?>
