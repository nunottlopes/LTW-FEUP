<?php
require_once __DIR__ . '/../../../api/api.php';
require_once API::entity('image');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'image';

$methods = ['GET', 'DELETE'];

$actions = [
    'get-id'     => ['GET', ['imageid']],
    'get-all'    => ['GET', ['all']],

    'delete-id'  => ['DELETE', ['imageid']]
    // no delete-all for images
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * IMAGE: imageid, all
 */
// imageid
if (API::gotargs('imageid')) {
    $imageid = $args['imageid'];

    $image = Image::read($imageid);

    if (!$image) {
        HTTPResponse::notFound("Image with id $imageid");
    }
}

/**
 * 3. ANSWER: HTTPResponse
 */
// GET
if ($action === 'get-id') {
    HTTPResponse::ok("Image $imageid", $image);
}

if ($action === 'get-all') {
    $images = Image::readAll();

    HTTPResponse::ok("All images", $images);
}

if ($action === 'delete-id') {
    $auth = Auth::demandLevel('admin');

    $count = Image::delete($imageid);

    $data = [
        'count' => $count
    ];

    HTTPResponse::deleted("Deleted image with id $imageid", $data);
}
?>
