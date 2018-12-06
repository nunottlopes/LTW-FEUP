<?php
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
    public static function getAscendants(int $child) {
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
        $stmt->execute([$child]);
        $comments = static::fetchAll($stmt);

        $story = Story::read($comments[0]['parentid']);

        $line = [
            'story' => $story,
            'comments' => $comments
        ];

        return $line;
    }

    public static function getDescendants(int $parent) {
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
        $stmt->execute([$parent, $parent]);
        return static::fetchAll($stmt);
    }

    public static function getTree(int $parent) {
        $descendants = static::getDescendants($parent);
        if ($descendants == null) return null;

        $entities = keyfy($descendants, 'entityid');

        $top = Story::read($parent);

        if ($top == null) {
            $top = Comment::read($parent);
            if ($top == null) return false;
        }

        $entities[$parent] = $top;

        return static::buildTree($entities, $parent);
    }
}
?>
