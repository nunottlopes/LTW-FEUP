<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '../entities/user.php';

class Auth {
    private static function isAdminUser(int $userid) {
        return $userid === 0;
    }

    public static function login(string $name, string $password) {
        if (User::authenticate($name, $password, $error)) {
            $user = User::get($name);

            $_SESSION['userid'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['useremail'] = $user['email'];
            $_SESSION['login_timestamp'] = time();

            if (static::isAdminUser($user['userid'])) {
                $_SESSION['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
            }

            return true;
        } else {
            throw $error;
        }
    }

    public static function logout() {
        session_destroy();
        session_start();

        if (isset($_SESSION['userid'])) unset($_SESSION['userid']);
        if (isset($_SESSION['username'])) unset($_SESSION['username']);
        if (isset($_SESSION['useremail'])) unset($_SESSION['useremail']);
        if (isset($_SESSION['login_timestamp'])) unset($_SESSION['login_timestamp']);

        return true;
    }

    public static function loginRequest() {
        if (!isset($_GET['password'])) {
            throw new Error('No password provided');
        }

        $password = $_GET['password'];

        if (isset($_GET['username'])) {
            $username = $_GET['username'];
        } else if (isset($_GET['email'])) {
            $user = User::getByEmail($_GET['email']);
            $username = $user['username'];
        } else {
            throw new Error('No username provided');
        }

        return static::login($username, $password);
    }

    public static function logoutRequest() {
        return static::logout();
    }

    public static function level(string $level, int $userid = null) {
        switch ($level) {
        case 'free':
            return true;
        case 'loggedin':
            return (boolean)isset($_SESSION['userid']);
        case 'auth':
            return isset($_SESSION['userid']) && ($userid !== null)
                && ($_SESSION['userid'] === $userid || $_SESSION['admin']));
        case 'admin':
            return isset($_SESSION['userid']) && $_SESSION['admin'];
        default:
            throw new Error("Unhandled Auth level case: $level");
        }
    }

    public static function assertLevel(string $level, int $userid = null) {
        $authorized = static::level($level, $userid);

        if ($authorized) return true;

        switch ($level) {
        case 'loggedin':
            throw new Error('Must be logged in');
        case 'auth':
            throw new Error('Not authorized');
        case 'admin':
            throw new Error('Not authorized: administrative action');
        }
    }
}

?>
