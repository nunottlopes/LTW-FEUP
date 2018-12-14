<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/story.php';
require_once __DIR__ . '/comment.php';

class Tree extends APIEntity {
    /**
     * $more Default Constants
     */
    protected static $defaultSince = 0;
    protected static $defaultLimit = 75;
    protected static $defaultOffset = 0;
    protected static $defaultMaxDepth = 7;
    protected static $defaultSort = 'top';

    /**
     * AUXILIARY
     *
     * Extend a normal query's arguments $args with since, depth, limit and offset.
     * The query string ends with:
     *
     *  [AND|WHERE] depth <= ? AND createdat >= ? ... LIMIT ? OFFSET ?
     *                       |                  |           |        ^--- $offset
     *                       |                  |           ^--- $limit
     *                       |                  ^--- $since
     *                       ^--- $maxdepth
     *
     * So we push to $args array values $maxdepth, $since, $limit, $offset IN THIS ORDER.
     */
    private static function extend(array $args, array $more) {
        $maxdepth = static::maxdepth($more);
        $since = static::since($more);
        $limit = static::limit($more);
        $offset = static::offset($more);

        $args[] = $maxdepth;
        $args[] = $since;
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
    private static function sort($more) {
        $order = static::order($more);

        switch ($order) {
        case 'top':
            return 'score';
        case 'bot':
            return '-score';
        case 'new':
            return 'createdat';
        case 'old':
            return '-createdat';
        case 'best':
            return 'WILSONLOWERBOUND(upvotes, downvotes)';
        case 'controversial':
            return 'REDDITCONTROVERSIAL(upvotes, downvotes)';
        case 'hot':
            return 'REDDITHOT(upvotes, downvotes, createdat)';
        case 'average':
            return 'CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float)';
        default:
            return 'score'; // top
        }
    }

    /**
     * READ
     */
    public static function getAncestry(int $descendantid) {
        $query = '
            SELECT storyid FROM CommentExtra WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$descendantid]);
        $comment = static::fetch($stmt);

        if (!$comment) return [];

        $storyid = $comment['storyid'];

        $query = '
            SELECT * FROM CommentAncestryTree WHERE descendantid = ?
            ORDER BY level ASC;
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$descendantid]);
        $comments = static::fetchAll($stmt);

        $query = '
            SELECT * FROM StoryAll WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$storyid]);
        $story = static::fetch($stmt);

        $merge = [
            'story' => $story,
            'comments' => $comments
        ];

        return $merge;
    }

    private static function getAllDescendants(int $ascendantid, array $more) {
        $sort = static::sort($more);

        $query = "
            WITH Choice(ascendantid) AS (
                VALUES (?)
            ), Best(entityid) AS (
                SELECT entityid
                FROM CommentTree
                WHERE ascendantid IN Choice
                AND depth <= ? AND createdat >= ?
                ORDER BY $sort DESC
                LIMIT ? OFFSET ?
            ), BestAncestry(entityid) AS (
                SELECT Tree.ascendantid FROM Tree
                WHERE Tree.descendantid IN Best
            )
            SELECT *, $sort AS rating
            FROM CommentTree
            WHERE ascendantid IN Choice
            AND (entityid IN BestAncestry OR entityid IN Best)
            ORDER BY depth ASC, rating DESC, createdat DESC, entityid ASC;
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

    /**
     * VOTED READ
     */
    public static function getAncestryVoted(int $descendantid, int $userid) {
        $query = '
            SELECT storyid FROM CommentExtra WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$descendantid]);
        $comment = static::fetch($stmt);

        if (!$comment) return [];

        $storyid = $comment['storyid'];

        $query = '
            SELECT * FROM CommentAncestryVotingTree
            WHERE descendantid = ? AND userid = ?
            ORDER BY level ASC;
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$descendantid, $userid]);
        $comments = static::fetchAll($stmt);

        $query = '
            SELECT * FROM StoryVotingAll SA
            WHERE entityid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$storyid, $userid]);
        $story = static::fetch($stmt);

        $merge = [
            'story' => $story,
            'comments' => $comments
        ];

        return $merge;
    }

    private static function getAllDescendantsVoted(int $ascendantid,
            int $userid, array $more) {
        $sort = static::sort($more);

        $query = "
            WITH Choice(ascendantid) AS (
                VALUES (?)
            ), Best(entityid) AS (
                SELECT entityid
                FROM CommentTree CT
                WHERE CT.ascendantid IN Choice
                AND depth <= ? AND createdat >= ?
                ORDER BY $sort DESC
                LIMIT ? OFFSET ?
            ), BestAncestry(entityid) AS (
                SELECT Tree.ascendantid FROM Tree
                WHERE Tree.descendantid IN Best
            )
            SELECT *, $sort AS rating
            FROM CommentVotingTree
            WHERE ascendantid IN Choice AND userid = ?
            AND (entityid IN BestAncestry OR entityid IN Best)
            ORDER BY depth ASC, rating DESC, createdat DESC, entityid ASC;
            ";

        $queryArguments = static::extend([$ascendantid], $more);
        $queryArguments[] = $userid;

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getTreeVoted(int $ascendantid, int $userid, array $more) {
        $descendants = static::getAllDescendantsVoted($ascendantid, $userid, $more);

        $keyed = API::keyfy($descendants, 'entityid');

        return static::fixTree($keyed, $ascendantid, 1);
    }
}
?>
