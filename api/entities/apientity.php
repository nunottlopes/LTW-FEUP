<?php
require_once __DIR__ . '/../api.php';

class APIEntity {
    protected static function since($more) {
        if (isset($more['since'])) {
            $since = $more['since'];
            if (is_int($since) && $since > 0) {
                return $since;
            }
        }
        return static::$defaultSince;
    }

    protected static function limit($more) {
        if (isset($more['limit'])) {
            $limit = $more['limit'];
            if (is_int($limit) && $limit > 0) {
                return $limit;
            }
        }
        return static::$defaultLimit;
    }

    protected static function offset($more) {
        if (isset($more['offset'])) {
            $offset = $more['offset'];
            if (is_int($offset) && $offset >= 0) {
                return $offset;
            }
        }
        return static::$defaultOffset;
    }

    protected static function maxdepth($more) {
        if (isset($more['maxdepth'])) {
            $maxdepth = $more['maxdepth'];
            if (is_int($maxdepth) && $maxdepth > 0) {
                return $maxdepth;
            }
        }
        return static::$defaultMaxDepth;
    }

    protected static function fetch(PDOStatement &$stmt) {
        $fetch = $stmt->fetch();
        if ($fetch == null || $fetch === false) return $fetch;

        return API::cast($fetch);
    }

    protected static function fetchAll(PDOStatement &$stmt) {
        $fetches = $stmt->fetchAll();
        if ($fetches == null || $fetches === false) return $fetches;

        $object = [];
        
        foreach ($fetches as $fetch) {
            $casted = API::cast($fetch);

            array_push($object, $casted);
        }

        return $object;
    }
}
?>
