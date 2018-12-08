<?php
$action = 'valid';

$auth = Auth::demandLevel('free');

$channelname = $args['channelname'];

$valid = Channel::valid($channelname);

$valid_string = $valid ? "valid" : "invalid";

$data = [
    'channelname' => $channelname,
    'valid' => $valid,
    'message' => $valid_string
];

HTTPResponse::ok("Channel name $channelname $valid_string", $data);
?>
