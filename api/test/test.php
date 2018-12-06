<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

function brk() {
    echo '<br/><br/><br/><br/>';
}

function hdr(string $header = null) {
    if ($header != null) echo "<h3>$header</h3>";
}

function tprint($result, string $header = null) {
    hdr($header);
    echo '<pre>';
    echo json_encode($result, JSON_PRETTY_PRINT);
    echo '</pre>';
    echo '<br/>';
}

function eprint($error, string $header = null) {
    hdr($header);
    echo '<pre>';
    echo $error;
    echo '</pre>';
    echo '<br/>';
}

function test(callable $func, $args, string $header = null) {
    try {
        $result = $func(...$args);
        tprint($result, $header);
    } catch (Throwable $error) {
        eprint($error, $header);
    }
}

?>