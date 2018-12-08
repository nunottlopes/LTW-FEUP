<?php
$action = 'delete';

$storyid = $args['storyid'];

$story = Story::read($storyid);

if (!$story) {
    HTTPResponse::notFound("Story with id $storyid");
}

$authorid = $story['authorid'];

$auth = Auth::demandLevel('authid', $authorid);

$count = Story::delete($storyid);

$data = [
    'count' => $count,
    'story' => $story
]

HTTPResponse::deleted("Deleted story $storyid", $data);
?>
