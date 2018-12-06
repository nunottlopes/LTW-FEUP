<?php
require_once __DIR__ . '/apientity.php';

class Story extends APIEntity {    
    /**
     * CREATE
     */
    public static function create(string $channelid, int $authorid, string $title,
            string $type, string $content) {
        $query = '
            INSERT INTO Story(channelid, authorid, storyTitle, storyType, content)
            VALUES (?, ?, ?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        return $stmt->execute([$channelid, $authorid, $title, $type, $content]);
    }

    /**
     * READ
     */
    public static function getChannel(int $channelid) {
        $query = '
            SELECT * FROM StoryAll WHERE channelid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid]);
        return static::fetchAll($stmt);
    }

    public static function getUser(int $authorid) {
        $query = '
            SELECT * FROM StoryAll WHERE authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$authorid]);
        return static::fetchAll($stmt);
    }

    public static function getChannelUser(int $channelid, int $authorid) {
        $query = '
            SELECT * FROM StoryAll WHERE channelid = ? AND authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid, $authorid]);
        return static::fetchAll($stmt);
    }

    public static function read(int $id) {
        $query = '
            SELECT * FROM StoryAll WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return static::fetch($stmt);
    }

    public static function readAll() {
        $query = '
            SELECT * FROM StoryAll
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return static::fetchAll($stmt);
    }

    /**
     * UPDATE
     */
    public static function update(int $id, string $content) {
        $query = '
            UPDATE Story SET content = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        return $stmt->execute([$content, $id]);
    }
    
    /**
     * DELETE
     */
    public static function delete(int $id) {
        $query = '
            DELETE FROM Story WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>