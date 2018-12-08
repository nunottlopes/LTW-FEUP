<?php
$auth = Auth::demandLevel('free');

$storyid = $args['storyid'];

$story = Story::read($storyid);

if (!$story) {
    HTTPResponse::notFound("Story with id $storyid");
}

HTTPResponse::ok("Story $storyid", $story);
?>
