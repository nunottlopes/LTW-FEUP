<?php
$storyid = $args['storyid'];
$content = $args['content'];

$story = Story::read($storyid);

if (!$story) {
    HTTPResponse::notFound("Story with id $storyid");
}

$authorid = $story['authorid'];

$auth = Auth::demandLevel('authid', $authorid);

$count = Story::update($storyid, $content);

$data = [
    'count' => $count,
    'previous' => $story
];

HTTPResponse::updated("Story successfully updated", $data);
?>
