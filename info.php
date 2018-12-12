<?php

$array = [5 => 'a', 9 => 'b', 3 => 'c', 7 => 'd'];

echo json_encode($array, JSON_PRETTY_PRINT);

function abc(array &$ok) {
    unset($ok[3]);
}

abc($array);

echo json_encode($array, JSON_PRETTY_PRINT);

?>
