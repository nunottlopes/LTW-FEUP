<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('entity');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'entity';

$methods = ['GET'];

$actions = [
    'get-id'  => ['GET', ['entityid']],
    'get-all' => ['GET', ['all']]
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::requireMethod($methods);

$args = HTTPRequest::query($method, $actions, $action);

$auth = Auth::demandLevel('free');

/**
 * 2. GET: Check query parameter identifying resources
 * ENTITY: entityid, all
 */
// entityid
if (API::gotargs('entityid')) {
    $entityid = $args['entityid'];

    $entity = Entity::read($entityid);

    if (!$entity) {
        HTTPResponse::notFound("Entity with id $entityid");
    }
}

/**
 * 3. ANSWER: HTTPResponse
 */
// GET
if ($action === 'get-id') {
    HTTPResponse::ok("Entity $entityid", $entity);
}

if ($action === 'get-all') {
    $entities = Entity::readAll();

    HTTPResponse::ok("All entities", $entities);
}
?>
