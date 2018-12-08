<?php
require_once __DIR__ . '/../api.php';

function keyfy(array $array, string $key) {
    $object = [];

    foreach ($array as $el) {
        $object[$el[$key]] = $el;
    }

    return $object;
}

class APIEntity {
    public static function cast(array $fetch, bool $strict = false) {
        $object = [];

        foreach ($fetch as $key => $value) {
            switch ($key) {
            // IDs
            case 'userid':
            case 'entityid':
            case 'storyid':
            case 'commentid':
            case 'authorid':
            case 'creatorid':
            case 'channelid':
            case 'parentid':
                $object[$key] = (integer)$value;
                break;

            // Votes
            case 'upvotes':
            case 'downvotes':
            case 'votes':
                $object[$key] = (integer)$value;
                break;

            // Timestamps
            case 'createdat':
            case 'updatedat':
            case 'savedat':
            case 'date':
            case 'timestamp':
                $object[$key] = (integer)$value;
                break;

            // Text
            case 'name':
            case 'username':
            case 'authorname':
            case 'channelname':
            case 'email':
            case 'hash':
            case 'title':
            case 'type':
            case 'kind':
            case 'vote':
            case 'storyTitle':
            case 'storyType':
            case 'content':
                $object[$key] = $value;
                break;

            default:
                if ($strict) {
                    throw new Error("Unhandled APIEntity cast case: < $key >");
                }

                $object[$key] = $value;
                break;
            }
        }

        return $object;
    }

    protected static function fetch(PDOStatement &$stmt) {
        $fetch = $stmt->fetch();
        if ($fetch == null || $fetch === false) return $fetch;

        return static::cast($fetch, true);
    }

    protected static function fetchAll(PDOStatement &$stmt) {
        $fetches = $stmt->fetchAll();
        if ($fetches == null || $fetches === false) return $fetches;

        $object = [];
        
        foreach ($fetches as $fetch) {
            $casted = static::cast($fetch, true);

            array_push($object, $casted);
        }

        return $object;
    }
}
?>
