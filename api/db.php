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
            '', '', [/*PDO::ATTR_PERSISTENT => true*/]);

        if (static::$db == null) {
            throw new Error("Failed to open database at $path");
        }
        
        static::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        static::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        static::$db->query('PRAGMA foreign_keys=ON');

        // Add Reddit BEST sorting function
        static::$db->sqliteCreateFunction('WILSONLOWERBOUND', '\wilson_lower_bound', 2);

        // Add Reddit HOT sorting function
        static::$db->sqliteCreateFunction('REDDITHOT', '\reddit_hot', 3);

        // Add Reddit CONTROVERSIAL sorting function
        static::$db->sqliteCreateFunction('REDDITCONTROVERSIAL', '\reddit_controversial', 2);
    }
}

function wilson_lower_bound(int $upvotes, int $downvotes) {
    if ($upvotes === 0) $upvotes = 1;
    $sum = $upvotes + $downvotes;
    $mul = $upvotes * $downvotes;
    $z = 1.960; // 95% = 1.960; 90% = 1.645; 99% = 2.576
    return (($upvotes + 1.9208) / $sum - $z * sqrt($mul / $sum + 0.9604) / $sum) / (1 + 3.8416 / $sum);
}

function reddit_hot(int $upvotes, int $downvotes, int $createdat) {
    $d = mktime(7, 46, 43, 12, 8, 2005);
    $t = $createdat - $d;
    $x = $upvotes - $downvotes;
    $y = ($x > 0) ? 1 : (($x < 0) ? -1 : 0);
    $z = max($x, 1);
    return log($z, 10) + ($y * $t) / 45000;
}

function reddit_controversial(int $upvotes, int $downvotes) {
    $sum = $upvotes + $downvotes;
    $dif = $upvotes - $downvotes;
    return $sum / max(abs($dif), 1); // admittedly, this could be inline SQL...
}
?>
