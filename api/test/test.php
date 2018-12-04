<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

function brk() {
    echo '<br/><br/><br/>';
}

function hdr(string $header = null) {
    if ($header != null) echo "<h3>$header</h3>";
}

function ftr($error) {
    if ($error != null) echo "<h4>$error</h4>";
}

function tprint($object, string $header = null) {
    echo '<pre>';
    hdr($header);
    echo json_encode($object, JSON_PRETTY_PRINT);
    echo '</pre>';
    echo '<br/>';
}

function eprint($error, string $header = null) {
    echo '<pre>';
    hdr($header);
    echo $error;
    echo '</pre>';
    echo '<br/>';
}

function keyfy(iterable $array, string $key) {
    $object = [];

    foreach ($array as $el) {
        $object[$el[$key]] = $el;
    }

    return $object;
}

?>