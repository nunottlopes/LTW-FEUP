<?php
$action = 'read';

$auth = Auth::demandLevel('free');

$storyid = (int)$args['storyid'];

$story = Story::read($storyid);

if (!$story) {
    HTTPResponse::notFound("Story with id $storyid");
}

HTTPResponse::ok("Story $storyid", $story);
?>
