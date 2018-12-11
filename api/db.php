<?php
/**
 * Database connection class.
 *
 * PDO object accessed through DB::get()
 */
class DB {
    private static $db;
    private static $apipath = '/api/api.db';

    /**
     * Get the PDO object.
     */
    public static function get() {
        if (static::$db == null) static::open();
        return static::$db;
    }

    /**
     * Open the database connection. Assumes $db is uninstantiated.
     */
    private static function open() {
        $path = $_SERVER['DOCUMENT_ROOT'] . static::$apipath;

        static::$db = new PDO('sqlite:' . $path, 
            '', '', [PDO::ATTR_PERSISTENT => true]);

        if (static::$db == null) {
            throw new Error("Failed to open database at $path");
        }
        
        static::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        static::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        static::$db->query('PRAGMA foreign_keys=ON');

        // Add SQRT function
        static::$db->sqliteCreateFunction('SQRT', 'sqrt', 1);

        // Add BEST sorting function
        static::$db->sqliteCreateFunction('WILSONBEST', 'wilsonBestSort', 2);
    }
}

function wilsonBestSort(int $upvotes, int $downvotes) {
    return (($upvotes + 1.9208) / ($upvotes + $downvotes) -
        1.96 * sqrt(($upvotes * $downvotes) / ($upvotes + $downvotes) + 0.9604) /
        ($upvotes + $downvotes)) / (1 + 3.8416 / ($upvotes + $downvotes))
}
?>
