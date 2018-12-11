<?php
require_once __DIR__ . '/../api.php';

class APIEntity {
    /**
     * $more Default constants
     */
    private const defaultSince = 0;
    private const defaultLimit = 50;
    private const defaultOffset = 0;

    /**
     * $more get functions
     */
    private static function since($since) {
        if (is_int($since) && $since > 0) {
            return $since;
        } else {
            return static::defaultSince;
        }
    }

    private static function limit($limit) {
        if (is_int($limit) && $limit > 0) {
            return $limit;
        } else {
            return static::defaultLimit;
        }
    }

    private static function offset($offset) {
        if (is_int($offset) && $offset >= 0) {
            return $offset;
        } else {
            return static::defaultOffset;
        }
    }

    /**
     * Extend a normal query's arguments $args with since, limit and offset.
     * The query string ends with:
     *
     *      [AND|WHERE] createdat > ? LIMIT ? OFFSET ?
     *                              ^       ^        ^- $offset
     *                              |       +--- $limit
     *                              +--- $since
     * 
     * So we push to $args array values $since, $limit and $offset IN THIS ORDER.
     */
    protected static function extend(array $args = [], array $more = null) {
        $since = static::since($more['since']);
        $limit = static::limit($more['limit']);
        $offset = static::offset($more['offset']);

        $args[] = $since;
        $args[] = $limit;
        $args[] = $offset;

        return $args;
    }

    /**
     * Call $stmt->fetch() and cast the result.
     */
    protected static function fetch(PDOStatement &$stmt) {
        $fetch = $stmt->fetch();
        if ($fetch == null || $fetch === false) return $fetch;

        return API::cast($fetch);
    }

    /**
     * Call $stmt->fetchAll() and cast the result.
     */
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
