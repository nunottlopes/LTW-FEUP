<?php
require_once __DIR__ . '/../api.php';

class APIEntity {
    protected static function since($since) {
        if (is_int($since) && $since > 0) {
            return $since;
        } else {
            return static::defaultSince;
        }
    }

    protected static function limit($limit) {
        if (is_int($limit) && $limit > 0) {
            return $limit;
        } else {
            return static::defaultLimit;
        }
    }

    protected static function offset($offset) {
        if (is_int($offset) && $offset >= 0) {
            return $offset;
        } else {
            return static::defaultOffset;
        }
    }

    protected static function maxdepth($maxdepth) {
        if (is_int($maxdepth) && $maxdepth > 0) {
            return $maxdepth;
        } else {
            return static::defaultMaxDepth;
        }
    }

    protected static function depth($depth) {
        if (is_int($depth) && $depth > 0) {
            return $depth;
        } else {
            return static::defaultDepth;
        }
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
