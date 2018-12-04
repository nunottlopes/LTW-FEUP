<?php

function brk() {
    echo '<br/><br/><br/>';
}

function tprint($object, $header = null) {
    echo '<pre>';
    if ($header != null) echo "<h4>$header</h4>";
    echo json_encode($object, JSON_PRETTY_PRINT);
    echo '</pre>';
    echo '<br/>';
}

function keyfy($array, $key) {
    $object = [];

    foreach ($array as $el) {
        $object[$el[$key]] = $el;
    }

    return $object;
}

?>