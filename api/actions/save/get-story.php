<?php
$auth = Auth::demandLevel('admin');

$storyid = $args['storyid'];

if (!Story::read($storyid)) {
    HTTPResponse::notFound("Story with id $storyid");
}

$saves = Save::getStory($storyid);

HTTPResponse::ok("All saves of story $storyid", $saves);
?>
