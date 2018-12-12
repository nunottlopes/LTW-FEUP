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
    protected static $defaultSortTable = 'CommentTreeSortBest';

    /**
     * AUXILIARY
     * 
     * Extend a normal query's arguments $args with since, depth, limit and offset.
     * The query string ends with:
     *
     *  [AND|WHERE] AND depth <= ?
     *                           ^--- $maxdepth
     * 
     * So we push to $args array value $maxdepth.
     */
    private static function extend(array $args, array $more) {
        $maxdepth = static::maxdepth($more);
        $limit = static::limit($more);
        $offset = static::offset($more);

        $args[] = $maxdepth;
        $args[] = $limit;
        $args[] = $offset;

        return $args;
    }

    /**
     * Fix SQL's sort of a comment tree.
     */
    private static function fixTree(array &$all, int $parentid, int $depth) {
        $tree = [];

        foreach ($all as $entityid => $comment) {
            if ($comment['parentid'] === $parentid) {
                $tree[$entityid] = $comment;
            }
        }

        if ($tree === []) return $tree;

        ++$depth;

        foreach ($tree as $entityid => $comment) {
            unset($all[$entityid]);
        }

        foreach ($tree as $entityid => $comment) {
            $tree[$entityid]['children'] = static::fixTree($all, $entityid, $depth);
        }

        return API::unkeyfy($tree);
    }

    /**
     * Select the appropriate story table view based on sorting desired.
     *
     * Switch statement prevents SQL injection.
     */
    private static function sortTablename($more) {
        if (!isset($more['order'])) return static::$defaultSortTable;

        $order = $more['order'];
        if (!is_string($order)) return static::$defaultSortTable;

        switch ($order) {
        case 'top': return 'CommentTreeSortTop';
        case 'bot': return 'CommentTreeSortBot';
        case 'new': return 'CommentTreeSortNew';
        case 'old': return 'CommentTreeSortOld';
        case 'best': return 'CommentTreeSortBest';
        case 'controversial': return 'CommentTreeSortControversial';
        case 'average': return 'CommentTreeSortAverage';
        case 'hot': return 'CommentTreeSortHot';
        case 'all':
        default: return static::$defaultSortTable;
        }
    }

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
            SELECT * FROM CommentAncestryTree WHERE descendantid = ?
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

    public static function getAllDescendants(int $ascendantid, array $more) {
        $sorttable = static::sortTablename($more);

        $query = "
            WITH Choice(ascendantid) AS (
                VALUES (?)
            ),   Best(entityid) AS (
                SELECT entityid
                FROM $sorttable CT
                WHERE CT.ascendantid IN Choice
                AND depth <= ?
                LIMIT ? OFFSET ?
            ),   BestAncestry(entityid) AS (
                SELECT Tree.ascendantid FROM Tree
                WHERE Tree.descendantid IN Best
            )
            SELECT *
            FROM $sorttable
            WHERE ascendantid IN Choice
            AND (entityid IN BestAncestry OR entityid IN Best)
            ORDER BY depth ASC, rating DESC;
            ";

        $queryArguments = static::extend([$ascendantid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getTree(int $ascendantid, array $more) {
        $descendants = static::getAllDescendants($ascendantid, $more);

        $keyed = API::keyfy($descendants, 'entityid');

        return static::fixTree($keyed, $ascendantid, 1);
    }
}
?>
