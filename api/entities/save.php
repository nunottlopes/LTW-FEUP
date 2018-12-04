<?php
require_once '../config/db.php';

class Save {
    /**
     * CREATE
     */
    public static function create($entity, $user) {
        $query = '
            INSERT INTO save(entity_id, user_id) VALUES (?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parent, $user]);
        return true;
    }

    /**
     * READ
     */
    public static function getUserSaves($user) {
        $query = '
            SELECT * FROM save
            LEFT JOIN story ON save.entity_id = story.entity_id
            LEFT JOIN comment on save.entity_id = comment.entity_id
            WHERE save.user_id = ?
            ORDER BY save.created_at DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$user]);
        return $stmt->fetchAll();
    }

    public static function getUserStorySaves($user) {
        $query = '
            SELECT * FROM save JOIN story
            WHERE user_id = ?
            ORDER BY created_at DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$user]);
        return $stmt->fetchAll();
    }

    public static function getUserStorySaves($user) {
        $query = '
            SELECT * FROM save JOIN comment
            WHERE user_id = ?
            ORDER BY created_at DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$user]);
        return $stmt->fetchAll();
    }

    /**
     * NO UPDATE
     * There's nothing to be updated.
     */
    
    /**
     * DELETE
     */
    public static function delete($entity, $user) {
        $query = '
            DELETE FROM save WHERE entity_id = ? AND user_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entity, $user]);
        return true;
    }
}
?>