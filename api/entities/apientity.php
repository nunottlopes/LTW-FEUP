<?php
require_once __DIR__ . '/../api.php';

class APIEntity {
    protected static function fetch(PDOStatement &$stmt) {
        $fetch = $stmt->fetch();
        if ($fetch == null || $fetch === false) return $fetch;

        return API::cast($fetch, true);
    }

    protected static function fetchAll(PDOStatement &$stmt) {
        $fetches = $stmt->fetchAll();
        if ($fetches == null || $fetches === false) return $fetches;

        $object = [];
        
        foreach ($fetches as $fetch) {
            $casted = API::cast($fetch, true);

            array_push($object, $casted);
        }

        return $object;
    }
}
?>
