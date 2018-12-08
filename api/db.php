<?php
class DB {
    private static $db;
    private static $apipath = 'api/api.db';

    // Get the PDO object
    public static function get() {
        if (static::$db == null) static::open();
        return static::$db;
    }

    // Open the PDO connection to the SQLite server
    public static function open() {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/' . static::$apipath;

        static::$db = new PDO('sqlite:' . $path, 
            '', '', [PDO::ATTR_PERSISTENT => true]);

        if (static::$db == null) {
            throw new Error("Failed to open database $path");
        }
        
        static::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        static::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        static::$db->query('PRAGMA foreign_keys=ON');
    }
}
?>
