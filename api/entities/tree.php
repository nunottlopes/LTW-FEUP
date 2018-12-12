<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/story.php';
require_once __DIR__ . '/comment.php';

class Tree extends APIEntity {
    /**
     * $more Default Constants
     */
    protected static $defaultSince = 0;
    protected static $defaultLimit = 50;
    protected static $defaultOffset = 0;
    protected static $defaultMaxDepth = 4;

    /**
     * Extend a normal query's arguments $args with since, depth, limit and offset.
     * The query string ends with:
     *
     *  [AND|WHERE] createdat >= ? AND depth <= ? LIMIT ? OFFSET ?
     *                           ^              ^       ^        ^- $offset
     *                           |              |       +--- $limit
     *                           |              +--- $maxdepth
     *                           +--- $since
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
     * AUXILIARY
     */
    private static function buildTree(array $descendants, int $parentid) {
        $node = $descendants[$parentid];
        $node['children'] = [];

        $entities[$id] = null;

        foreach ($entities as $entityId => $entity) {
            if ($entity['parentid'] === $id) {
                $node['children'][$entityId] = static::buildTree($entities, $entityId);
            }
        }

        return $node;
    }

    /**
     * READ
     */
    public static function getAncestry(int $descendantid) {
        $query = '
            SELECT * FROM CommentAscendantTree WHERE descendantid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$descendantid]);
        $comments = static::fetchAll($stmt);

        $query = '
            SELECT * FROM StoryTree WHERE commentid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$descendantid]);
        $story = static::fetch($stmt);

        $line = [
            'story' => $story,
            'comments' => $comments
        ];

        return $line;
    }

    public static function getAllDescendants(int $ascendantid) {
        $query = '
            SELECT * FROM CommentTree WHERE ascendantid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$ascendantid]);
        return static::fetchAll($stmt);
    }

    public static function getTree(int $ascendantid) {
        $descendants = static::getAllDescendants($ascendantid);

        return static::buildTree($descendants, $ascendantid);
    }
}
?>
