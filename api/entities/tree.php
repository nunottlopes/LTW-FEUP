<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/story.php';
require_once __DIR__ . '/comment.php';

class Tree extends APIEntity {
    /**
     * AUXILIARY
     */
    private static function buildTree(array $entities, int $id) {
        $node = $entities[$id];
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
    public static function getAncestry(int $childid) {
        $query = '
            WITH RECURSIVE Subtree(id) AS (
                VALUES(?)
                UNION
                SELECT parentid FROM Comment JOIN Subtree
                WHERE Comment.entityid = Subtree.id
            )
            SELECT * FROM CommentAll
            WHERE entityid IN Subtree
            ORDER BY createdat ASC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$childid]);
        $comments = static::fetchAll($stmt);

        $story = Story::read($comments[0]['parentid']);

        $line = [
            'story' => $story,
            'comments' => $comments
        ];

        return $line;
    }

    public static function getDescendants(int $parentid) {
        $query = '
            WITH RECURSIVE Subtree(id) AS (
                VALUES(?)
                UNION
                SELECT entityid FROM Comment JOIN Subtree
                WHERE Comment.parentid = Subtree.id
            )
            SELECT * FROM CommentAll
            WHERE entityid IN Subtree AND entityid != ?
            ORDER BY createdat DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parentid, $parentid]);
        return static::fetchAll($stmt);
    }

    public static function getTree(int $parentid) {
        $top = Story::read($parentid);

        if ($top == null) {
            $top = Comment::read($parentid);
            if ($top == null) return false;
        }

        $descendants = static::getDescendants($parentid);

        $entities = API::keyfy($descendants, 'entityid');

        $entities[$parentid] = $top;

        return static::buildTree($entities, $parentid);
    }
}
?>
