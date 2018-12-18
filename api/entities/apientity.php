<?php
require_once __DIR__ . '/../api.php';

class APIEntity {
    /**
     * Get $since from $more or use default value.
     */
    protected static function since($more) {
        if ($more && isset($more['since'])) {
            $since = $more['since'];
            if (is_int($since) && $since > 0) {
                return $since;
            }
        }
        return static::$defaultSince;
    }

    /**
     * Get $limit from $more or use default value.
     */
    protected static function limit($more) {
        if ($more && isset($more['limit'])) {
            $limit = $more['limit'];
            if (is_int($limit) && $limit > 0) {
                return $limit;
            }
        }
        return static::$defaultLimit;
    }

    /**
     * Get $offset from $more or use default value.
     */
    protected static function offset($more) {
        if ($more && isset($more['offset'])) {
            $offset = $more['offset'];
            if (is_int($offset) && $offset >= 0) {
                return $offset;
            }
        }
        return static::$defaultOffset;
    }

    /**
     * Get $maxdepth from $more or use default value.
     */
    protected static function maxdepth($more) {
        if ($more && isset($more['maxdepth'])) {
            $maxdepth = $more['maxdepth'];
            if (is_int($maxdepth) && $maxdepth > 0) {
                return $maxdepth;
            }
        }
        return static::$defaultMaxDepth;
    }

    /**
     * Get $order from $more or use default value.
     */
    protected static function order($more) {
        if ($more && isset($more['order'])) {
            $order = $more['order'];
            if (is_string($order)) {
                return $order;
            }
        }
        return static::$defaultSort;
    }

    /**
     * Wrapper around $stmt->fetch, handling null erasing and type conversion.
     */
    protected static function fetch(PDOStatement &$stmt, $safe = []) {
        $fetch = $stmt->fetch();
        if ($fetch == null || $fetch === false) return $fetch;

        return API::cast(API::nonull($fetch, $safe));
    }

    /**
     * Idem for fetchAll.
     */
    protected static function fetchAll(PDOStatement &$stmt, $safe = []) {
        $fetches = $stmt->fetchAll();
        if ($fetches == null || $fetches === false) return $fetches;
        
        $new = [];
        foreach ($fetches as $fetch) {
            $new[] = API::cast(API::nonull($fetch, $safe));
        }

        return $new;
    }
}
?>
