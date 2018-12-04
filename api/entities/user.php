<?php
require_once __DIR__ . '/../config/db.php';

class User {
    private static $usernameRegex = '/^[a-zA-Z][a-zA-Z0-9_+-]{2,31}$/i';
    private static $hashOpt = ['cost' => 10];

    /**
     * VALIDATION
     *
     * Não sei que funções abaixo vão ser úteis. Escolham as mais apropriadas,
     * e apaguem ou ignorem as outras que forem desnecessárias.
     *
     * valid -> true or false
     * check -> void or throw
     */
    public static function validUsername(string $username) {
        return preg_match(static::$usernameRegex, $username) === 1;
    }

    public static function validEmail(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function valid(string $username, string $email, &$error = null) {
        if (!static::validUsername($username)) {
            $error = 'Invalid username';
            return false;
        }

        if (!static::validEmail($email)) {
            $error = 'Invalid email';
            return false;
        }

        return true;
    }

    public static function checkUsername(string $username) {
        if (!static::validUsername($username)) {
            throw new Error('Invalid username');
        }
    }

    public static function checkEmail(string $email) {
        if (!static::validEmail($email)) {
            throw new Error('Invalid email');
        }
    }

    public static function check(string $username, string $email) {
        if (!static::valid($username, $email, $error)) {
            throw new Error($error);
        }
    }

    /**
     * CREATE
     */
    public static function create(string $username, string $email, string $password) {
        static::check($username, $email);

        $hash = password_hash($password, PASSWORD_DEFAULT, static::$hashOpt);

        $query = '
            INSERT INTO user(username, email, hash) VALUES (?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$username, $email, $hash]);
        return true;
    }

    /**
     * READ
     */
    public static function getByUsername(string $username) {
        $query = '
            SELECT * FROM user WHERE username = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public static function getByEmail(string $email) {
        $query = '
            SELECT * FROM user WHERE email = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function get(string $name) {
        if (static::validUsername($name)) {
            return static::getByUsername($name);
        }

        if (static::validEmail($name)) {
            return static::getByEmail($name);
        }
        
        return false;
    }

    public static function read(int $id) {
        $query = '
            SELECT * FROM user WHERE user_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function readAll() {
        $query = '
            SELECT * FROM user
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * AUTHENTICATE
     */
    public static function authenticate(string $name, string $password, &$error = null) {
        $user = static::get($name);

        if (!$user) {
            $error = 'User not found';
            return false;
        }

        if (!password_verify($password, $user['hash'])) {
            $error = 'Wrong password';
            return false;
        }

        return true;
    }

    /**
     * NO UPDATE FOR NOW
     */
    
    /**
     * NO DELETE FOR NOW
     */
}
?>
