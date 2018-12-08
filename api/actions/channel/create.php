<?php
$action = 'create';

$auth = Auth::demandLevel('auth');

$channelname = $args['channelname'];
$creatorid = (int)$auth['userid'];

$channelid = Channel::create($channelname, $creatorid);

if (!$channelid) {
    HTTPResponse::conflict("Already existing channel", 'channelname', $channelname);
}

$data = [
    'channelid' => $channelid
];

HTTPResponse::created("Created channel $channelid", $data);
?>
