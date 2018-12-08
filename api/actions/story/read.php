<?php
$action = 'read';

$user = Auth::demandLevel('free');

$storyid = (int)$args['storyid'];

$story = Story::read($storyid);

if (!$story) {
    HTTPResponse::notFound("Story with id $storyid");
}

HTTPResponse::ok("Story $storyid", $story);
?>
