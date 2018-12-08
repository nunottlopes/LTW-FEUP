<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

/**
 * Error reporting.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * API generic utilities
 * Little functionality, name might be misleading.
 */
class API {
    /**
     * Cast array keys to appropriate type.
     * Used by database entities for database fetches and client argument parsing.
     */
    public static function cast(array $data) {
        $casted = [];

        foreach ($data as $key => $value) {
            // IDs
            if (preg_match('/^\w*id$/', $key)) {
                $casted[$key] = (int)$value;
                continue;
            }

            // Votes
            if (preg_match('/^\w*votes\w*$/', $key)) {
                $casted[$key] = (int)$value;
                continue;
            }

            // Timestamps
            if (preg_match('/^(?:\w+at|\w*date|\w*time|timestamp)$/', $key)) {
                $casted[$key] = (int)$value;
                continue;
            }

            // Numbers
            if (preg_match('/^(?:\w*number\w*|\w+num)$/', $key)) {
                $casted[$key] = (int)$value;
            }

            // Default text
            $casted[$key] = $value;
        }

        return $casted;
    }

    /**
     * Redirect to chosen action file.
     */
    public static function action(string $chosen) {
        global $resource, $supported, $parameters, $method, $args, $auth, $action;
        $action = $chosen;
        $file = $_SERVER['DOCUMENT_ROOT'] . "/api/actions/$resource/$chosen.php";
        require_once $file;
    }

    /**
     * Directory of entity
     */
    public static function entity(string $entity) {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/api/entities/$entity.php";
        return $file;
    }

    /**
     * Directory of resource
     */
    public static function resource(string $resource) {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/api/public/$resource.php";
        return $file;
    }

    /**
     * Check if global $args has the specified keys.
     */
    public static function gotargs(string ...$keys) {
        global $args;
        foreach ($keys as $key) {
            if (!isset($args[$key])) return false;
        }
        return true;
    }

    /**
     * Turns an array of arrays into a dictionary according to some key
     * present in every element.
     */
    public static function keyfy(array $array, string $key) {
        $object = [];
        foreach($array as $el) {
            $object[$el[$key]] = $el;
        }
        return $object;
    }
}

// Class Auth needs User entity
require_once API::entity('user');

/**
 * Class managing user authentication and resource access authorization levels
 */
class Auth {
    private static $authRegex = '/^Basic ((?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?)$/';

    private static $authorizationParsed = false;
    private static $authorizationUser = null;

    /**
     * Check if userid is an admin.
     * Admin specs might change in the future, e.g. another column on the
     * database's user table specifying authorization level, or specific username.
     */
    public static function isAdminUser(int $userid) {
        return $userid === 0;
    }

    /**
     * Authenticate a user without creating a logged in session.
     * 
     * Returns an object holding the userid, username and email if successful.
     * Returns false otherwise.
     */
    public static function autho(string $name, string $password, &$error = null) {
        if (User::authenticate($name, $password, $error)) {
            $user = User::get($name);

            if (static::isAdminUser($user['userid'])) {
                $user['admin'] = true;
            } else {
                $user['admin'] = false;
            }

            return $user;
        }
        
        return false;
    }

    /**
     * Authenticate a user and create a logged in session if successful.
     * 
     * Returns an object holding the userid, username and email if successful.
     * Returns false otherwise.
     * 
     * Failed authentication does not change state nor call an HTTPResponse method.
     *
     * It is assumed that a session has already been started.
     */
    public static function login(string $name, string $password, &$error = null) {
        if (User::authenticate($name, $password, $error)) {
            $user = User::get($name);

            $_SESSION['userid'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['useremail'] = $user['email'];
            $_SESSION['login_timestamp'] = time();

            if (static::isAdminUser($user['userid'])) {
                $_SESSION['admin'] = true;
                $user['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
                $user['admin'] = false;
            }

            return $user;
        }

        return false;
    }

    /**
     * Attempt to authenticate a user using the HTTP header 'Authorization'.
     *
     * If the header is not present the authentication fails.
     * If the header is present but the credentials are incorrect it fails too.
     *
     * Returns an array representing the authenticated user if successful, or false.
     */
    public static function authorization(bool $force = false) {
        // Cache
        if (static::$authorizationParsed) return static::$authorizationUser;

        static::$authorizationParsed = true;

        $header = HTTPRequest::header('Authorization');

        if ($header !== null) {
            // Regex parse
            if (!preg_match(static::$authRegex, $header, $matches)) {
                HTTPResponse::badHeader('Authorization', $header);
            }

            // Base 64 decode
            $string = base64_decode($matches[1], true);

            if (!$string) {
                HTTPResponse::badHeader('Authorization', $header);
            }

            // Username:Password split, might replace with regex parse in the future
            $split = explode(':', $string, 2);

            if (count($split) < 2) {
                HTTPResponse::badHeader('Authorization', $string);
            }

            $username = $split[0];
            $password = $split[1];

            $user = static::autho($username, $password, $error);

            if (!$user && $force) {
                HTTPResponse::unauthorized();
            }

            static::$authorizationUser = $user;
            return $user;
        }
        
        return false;
    }

    /**
     * Attempt to authenticate a user using the current session.
     * 
     * Returns an array representing the authenticated user if successful, or false.
     */
    public static function session(bool $force = false) {
        $auth = isset($_SESSION) && isset($_SESSION['userid']);

        if (!$auth && $force) HTTPResponse::unauthorized();
        if (!$auth) return false;

        $userid = $_SESSION['userid'];

        return [
            'userid' => $userid,
            'username' => $_SESSION['username'],
            'email' => $_SESSION['useremail'],
            'admin' => static::isAdminUser($userid)
        ];
    }

    /**
     * Practical authentication function. Authenticate the user using a mix of
     * session and basic authorization.
     *
     * If the session is not authenticated, authorization() is called.
     *
     * It is assumed that a session has already been started.
     */
    public static function authenticate() {
        $auth = static::session();
        if ($auth) return $auth;

        $auth = static::authorization();
        if ($auth) return $auth;

        return false;
    }

    /**
     * Checks whether the sent authorization header, if existent, provides
     * authorization to access a resource with a certain authorization level.
     *
     * Levels
     *   - open,
     *     free         Anyone can access the resource.
     *   - auth,
     *     user         Accessible if the user is authorized.
     *   - authid,
     *     userid       Accessible if the user is authorized as particular user.
     *   - privileged,
     *     admin        Authorized as admin.
     *   - none         Authorized as admin or not authorized.
     *
     * Returns
     *     true       if authentication is not achieved and not required.
     *     false      if authentication is not achieved and is required.
     *     user array if authentication is achieved and required.
     */
    public static function authLevel(string $level, int $userid = null,
            bool $force = true) {
        $auth = static::authorization();

        if (!$auth) {
            return $level === 'open' || $level === 'free' || $level === 'none';
        }

        $authid = $auth['userid'];
        $admin = $auth['admin'];

        switch ($level) {
        case 'open':
        case 'free':
        case 'auth':
        case 'user':
            $allow = true;
            break;
        case 'authid':
        case 'userid':
            if ($admin) { // admin impersonation
                $allow = User::read($userid) != null; // user exists?
                if (!$allow && $force) { // allow user not to exist?
                    HTTPResponse::notFound("User with id $userid");
                }
            } else {
                $allow = $authid === $userid; // equality implies existence.
            }
            break;
        case 'privileged':
        case 'admin':
        case 'none':
            $allow = $admin;
            break;
        default:
            $allow = false;
            break;
        }

        return $allow ? $auth : false;
    }

    /**
     * Checks whether the current session has authorization to access a resource with
     * a certain authorization level.
     *
     * Levels
     *   - open,
     *     free         Anyone can access the resource.
     *   - auth,
     *     user,
     *     login,
     *     logged,
     *     loggedin     Accessible if the user is logged in.
     *   - authid,
     *     userid,
     *     loginid,
     *     loggedid,
     *     loggedinid   Accessible if the user is logged in as a particular user.
     *   - privileged,
     *     admin        Logged in as admin.
     *   - none         Logged in as admin or not logged in.
     *
     * Returns
     *     true       if authentication is not achieved and not required.
     *     false      if authentication is not achieved and is required.
     *     user array if authentication is achieved and required.
     */
    public static function sessionLevel(string $level, int $userid = null,
            bool $force = true) {
        $auth = static::session();

        if (!$auth) {
            return $level === 'open' || $level === 'free' || $level === 'none';
        }

        $authid = $auth['userid'];
        $admin = $auth['admin'];

        switch ($level) {
        case 'open':
        case 'free':
        case 'auth':
        case 'login':
        case 'logged':
        case 'loggedin':
            $allow = true;
            break;
        case 'authid':
        case 'loginid':
        case 'user':
            // Action as another user
            if ($admin) {
                $allow = User::read($userid) != null; // user exists?
                if (!$allow && $force) { // allow user not to exist?
                    HTTPResponse::notFound("User with id $userid");
                }
            } else {
                $allow = $authid === $userid; // equality implies existence.
            }
            break;
        case 'privileged':
        case 'admin':
        case 'none':
            $allow = $admin;
            break;
        default:
            $allow = false;
            break;
        }

        return $allow ? $auth : false;
    }

    /**
     * Checks whether the client has authorization to access a resource with
     * a certain authorization level.
     *
     * Levels
     *   - open,
     *     free         Anyone can access the resource.
     *   - auth,
     *     user         Accessible if the user is authenticated.
     *   - login,
     *     logged,
     *     loggedin     Accessible if the user is logged in.
     *   - authid,
     *     userid       Accessible if the user is authenticated as a particular user.
     *   - loginid,
     *     loggedid,
     *     loggedinid   Accessible if the user is logged in as a particular user.
     *   - privileged,
     *     admin        Authenticated as admin.
     *   - none         Authenticated as admin or not authenticated.
     *
     * Returns
     *     true       if authentication is not achieved and not required.
     *     false      if authentication is not achieved and is required.
     *     user array if authentication is achieved and required.
     */
    public static function level(string $level, int $userid = null, bool $force = true) {
        if ($level === 'none') {
            $auth = static::authLevel($level, $userid, $force);
            $session = static::sessionLevel($level, $userid, $force);
            return ($auth && $session) ? $auth : false;
        } else {
            $auth = static::authLevel($level, $userid, $force);
            if ($auth) return $auth;

            $session = static::sessionLevel($level, $userid, $force);
            if ($session) return $session;

            return false;
        }
    }

    /**
     * Force authLevel to succeed.
     *
     * If it does not, an appropriate response is sent to the user.
     *
     * Response:
     *   - open,
     *     free         Succeeds.
     *   - auth,
     *     user         401 Unauthorized.
     *   - authid,
     *     userid       401 Unauthorized, user identified.
     *   - privileged,
     *     admin        403 Forbidden.
     */
    public static function demandAuthLevel(string $level, int $userid = null) {
        $auth = static::authLevel($level, $userid);

        if ($auth) return $auth;

        switch ($level) {
        case 'auth':
        case 'user':
        case 'none':
            HTTPResponse::unauthorized();
        case 'authid':
        case 'userid':
            HTTPResponse::unauthorized($userid);
        case 'privileged':
        case 'admin':
            HTTPResponse::forbidden();
        }
    }

    /**
     * Force sessionLevel to succeed.
     *
     * If it does not, an appropriate response is sent to the user.
     *
     * Response:
     *   - open,
     *     free         Succeeds.
     *   - auth,
     *     user,
     *     login,
     *     logged,
     *     loggedin     401 Unauthorized.
     *   - authid,
     *     userid,
     *     loginid,
     *     loggedid,
     *     loggedinid   401 Unauthorized, user identified.
     *   - privileged,
     *     admin        403 Forbidden.
     */
    public static function demandSessionLevel(string $level, int $userid = null) {
        $auth = static::sessionLevel($level, $userid);

        if ($auth) return $auth;

        switch ($level) {
        case 'auth':
        case 'user':
        case 'login':
        case 'logged':
        case 'loggedin':
        case 'none':
            HTTPResponse::unauthorized();
        case 'authid':
        case 'userid':
        case 'loginid':
        case 'loggedid':
        case 'loggedinid':
            HTTPResponse::unauthorized($userid);
        case 'privileged':
        case 'admin':
            HTTPResponse::forbidden();
        }
    }

    /**
     * Force level to succeed.
     *
     * If it does not, an appropriate response is sent to the user.
     *
     * Response:
     *   - open,
     *     free         Succeeds.
     *   - auth,
     *     user,
     *     login,
     *     logged,
     *     loggedin     401 Unauthorized.
     *   - authid,
     *     userid,
     *     loginid,
     *     loggedid,
     *     loggedinid   401 Unauthorized, user identified.
     *   - privileged,
     *     admin        403 Forbidden.
     */
    public static function demandLevel(string $level, int $userid = null) {
        $auth = static::level($level, $userid);

        if ($auth) return $auth;

        switch ($level) {
        case 'auth':
        case 'user':
        case 'login':
        case 'logged':
        case 'loggedin':
        case 'none':
            HTTPResponse::unauthorized();
        case 'authid':
        case 'userid':
        case 'loginid':
        case 'loggedid':
        case 'loggedinid':
            HTTPResponse::unauthorized($userid);
        case 'privileged':
        case 'admin':
            HTTPResponse::forbidden();
        }
    }

    /**
     * Logout and start a new session.
     * Idempotent, does not fail if there is no login.
     */
    public static function logout() {
        session_reset();
    }
}

/**
 * Utilities for parsing the client's HTTP request.
 */
class HTTPRequest {
    /**
     * Used to "set" the HTTP request method. Used for testing in query.php only.
     */
    public static $methodBackdoor = false;

    /**
     * Assume GET or HEAD and parse the query's key value pairs into a JSON.
     *
     * If $force is set all parameters searched must be present.
     */
    public static function parseQuery(array $parameters, bool $force = false) {
        $args = [];

        foreach ($parameters as $param) {
            if (!isset($_GET[$param])) {
                if ($force) HTTPResponse::missingParameter($param, $parameters);
            } else {
                $args[$param] = $_GET[$param];
            }
        }

        return API::cast($args);
    }

    /**
     * Assume POST and parse the the request's body into a JSON.
     *
     * If $force is set all parameters searched must be present.
     */
    public static function parsePOST(array $parameters, bool $force = false) {
        $args = [];

        foreach ($parameters as $param) {
            if (!isset($_GET[$param])) {
                if ($force) HTTPResponse::missingParameter($param, $parameters);
            } else {
                $args[$param] = $_POST[$param];
            }
        }

        return API::cast($args);
    }

    /**
     * Assume POST, PUT, ... and parse the request's body into a JSON.
     *
     * If $force is set all parameters searched must be present.
     */
    public static function parseBodyJSON(array $parameters, bool $force = false) {
        $args = [];

        $json = static::bodyJSON();

        foreach ($parameters as $param) {
            if (!isset($json[$param])) {
                if ($force) HTTPResponse::missingParameter($param, $parameters);
            } else {
                $args[$param] = $json[$param];
            }
        }

        return API::cast($args);
    }

    /**
     * Get request parameters according to request method.
     */
    public static function parse(array $parameters, bool $force = false) {
        $method = static::method();

        if ($method === 'GET' || $method === 'HEAD') {
            return static::parseQuery($parameters, $force);
        } else {
            return static::parseBodyJSON($parameters, $force);
        }
    }

    /**
     * Get the request method.
     */
    public static function method(array $supported = null, bool $force = false) {
        $method = $_SERVER['REQUEST_METHOD'];

        // Backdoor method for query.php
        if ($force && static::$methodBackdoor !== false) {
            return static::$methodBackdoor;
        } 

        // Just querying the actual method
        if ($supported === null) {
            return $method;
        }

        // Method is supported
        if (in_array($method, $supported, true)) {
            return $method;
        }

        // Method must be supported, answer client
        if ($force) {
            HTTPResponse::badMethod($supported);
        }
        
        return false;
    }

    /**
     * Get the request's query string.
     */
    public static function queryString() {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * Get the request's body string.
     */
    public static function bodyString() {
        return file_get_contents('php://input');
    }

    /**
     * Assume application/json and return the request's body as JSON.
     * If the body has invalid JSON a 400 HTTP response is sent to the client.
     */
    public static function bodyJSON() {
        $body = static::bodyString();
        $json = json_decode($body, true);

        if ($json === null) {
            HTTPResponse::malformedJSON($body, $parameters);
        }

        return $json;
    }

    /**
     * Get the request's headers.
     */
    public static function headers() {
        return apache_request_headers();
    }

    /**
     * Get a specific header field.
     */
    public static function header(string $header) {
        $headers = static::headers();

        if (isset($headers[$header])) {
            return $headers[$header];
        } else {
            return null; // maybe false?
        }
    }
}

/**
 * API's HTTP response abstractions.
 *
 * Calling any public method will terminate the script and answer the client.
 *
 * Every method assumes -nothing- has been echoed yet.
 */
class HTTPResponse {
    private static $authenticationRealm = 'FEUP News';

    /**
     * Output response body as JSON and exit.
     */
    private static function json(array $json) {
        header("Content-Type: application/json");

        if (HTTPRequest::method() !== 'HEAD') {
            echo json_encode($json, JSON_PRETTY_PRINT);
        }

        exit(0);
    }

    /**
     * Output response body as plain text JSON and exit.
     */
    private static function plain(array $json) {
        header("Content-Type: text/plain");

        if (HTTPRequest::method() !== 'HEAD') {
            echo json_encode($json, JSON_PRETTY_PRINT);
        }

        exit(0);
    }

    /**
     * Append several variables to a JSON, output response body and exit.
     * Redirects to json() for output. plain() not used as of now.
     */
    private static function success(string $code, string $message, array $data = null) {
        global $resource, $methods, $method, $parameters, $args, $auth, $actions, $action;

        $json = [
            'message' => $message,          // User readable [success] message
            'code' => $code,                // Machine readable [success] message
            'args' => $args,                // Client provided arguments
            'auth' => $auth,                // Request performed as this user
            'resource' => $resource,        // Resource accessed
            'action' => $action,            // Action performed on this resource
            'method' => $method,            // HTTP request method
            //'methods' => $methods,          // Methods supported on this resource
            //'parameters' => $parameters,    // Parameters supported on this resource
            //'actions' => $actions,          // Actions supported on this resource
            'data' => $data
        ];

        static::json($json);
    }

    /**
     * Like output() but intended for error messages.
     */
    private static function error(string $code, string $error, array $data = null) {
        global $resource, $methods, $method, $parameters, $args, $auth, $actions, $action;

        $json = [
            'message' => $error,            // User readable [error] message
            'error' => $error,              // User readable [error] message
            'code' => $code,                // Machine readable [error] message
            'args' => $args,                // Client provided arguments, possibly null
            'auth' => $auth,                // Request performed as this user
            'resource' => $resource,        // Resource accessed
            'action' => $action,            // Action performed on this resource
            'method' => $method,            // HTTP request method
            'methods' => $methods,          // Methods supported on this resource
            'parameters' => $parameters,    // Parameters supported on this resource
            'actions' => $actions,          // Actions supported on this resource
            'data' => $data
        ];

        static::json($json);
    }

    /**
     * HTTP Server Response Methods
     *
     * Most methods accept argument $message, holding a human readable description
     * of the result, and $data, holding the specifically requested resource
     * or other willingly given data.
     */
    
    /**
     * 200 OK
     * No arguments provided, querying resource.
     */
    public static function look(string $message, array $extra = []) {
        http_response_code(200);

        global $methods, $parameters, $actions;

        $look = [
            'methods' => $methods,
            'parameters' => $parameters,
            'actions' => $actions
        ];

        $data = $look + $extra;

        static::success('Query resource', $message, $data);
    }

    /**
     * 200 OK
     */
    public static function ok(string $message, array $data) {
        http_response_code(200);

        static::success('OK', $message, $data);
    }

    /**
     * 200 OK
     */
    public static function updated(string $message, array $data = null) {
        http_response_code(200);

        static::success('Updated', $message, $data);
    }

    /**
     * 200 OK
     */
    public static function deleted(string $message, array $data = null) {
        http_response_code(200);

        static::success('Deleted', $message, $data);
    }

    /**
     * 201 Created
     */
    public static function created(string $message, array $data = null) {
        http_response_code(201);

        static::success('Created', $message, $data);
    }

    /**
     * 202 Accepted
     */
    public static function accepted(string $message, array $data = []) {
        http_response_code(202);

        static::success('Accepted', $message, $data);
    }

    /**
     * 204 No Content
     * No response body.
     */
    public static function okNoContent() {
        http_response_code(204);

        exit(0);
    }

    /**
     * 205 Reset Content
     * No response body.
     */
    public static function okResetContent() {
        http_response_code(205);

        exit(0);
    }

    /**
     * 400 Bad Request
     * The request performed on the specified entity was successfully deduced to an
     * action based on the arguments provided, but that action requires an
     * argument which was not provided.
     */
    public static function missingParameter(string $missing, array $parameters) {
        http_response_code(400);

        $error = "Required parameter(s) not present: \"$missingstring\"";

        $data = [
            'missing' => $missing,
            'parameters' => $parameters
        ];

        static::error('Missing Parameter', $error, $data);
    }

    /**
     * 400 Bad Request
     * The request performed on the specified entity was successfully deduced to an
     * action based on the arguments provided, but that action requires one or more
     * arguments which were not provided.
     */
    public static function missingParameters(array $parameters) {
        http_response_code(400);

        $missing = [];

        foreach ($parameters as $param) {
            if (!gotargs($param)) $missing[] = $param;
        }

        $missingstring = implode(', ', $missing);


        if ($missingstring === 'confirm') {
            // Certain requests require 'confirm' argument (creates and updates).
            $error = "Please confirm action with \"confirm\" argument";
        } else if ($missingstring === 'confirm-delete') {
            // Irreversible delete requests require 'confirm-delete' argument.
            $error = "Please confirm irreversible delete with \"confirm-delete\"";
        } else {
            // Generic
            $error = "Required parameter(s) not present: \"$missingstring\"";
        }

        $data = [
            'missing' => $missing,
            'parameters' => $parameters,
            'text' => $missingstring
        ];

        static::error('Missing Parameters', $error, $data);
    }

    /**
     * 400 Bad Request
     * The request performed on the specified entity was successfully deduced to an
     * action based on the arguments provided, but a particular argument had an
     * incorrect value (probably of the wrong type).
     */
    public static function badArgument(string $param, string $value) {
        http_response_code(400);

        $error = "Parameter \"$param\" has an unexpected value";

        $data = [
            'param' => $param,
            'key' => $param,
            'value' => $value
        ];

        static::error('Bad Argument', $error, $data);
    }

    /**
     * 400 Bad Request
     * The request body was treated as a JSON and the parsing was unsuccessful.
     */
    public static function malformedJSON() {
        http_response_code(400);

        $error = "Request body contains marformed JSON";

        $data = [
            'body' => HTTPRequest::bodyString()
        ];

        static::error('Malformed JSON', $error, $data);
    }

    /**
     * 400 Bad Request
     * A request header sent has an invalid/unexpected value.
     */
    public static function badHeader(string $header, string $value) {
        http_response_code(400);

        $error = "Header $header has an unexpected value: $value";

        $data = [
            'header' => $header,
            'value' => $value
        ];

        static::error('Bad Header', $error, $data);
    }

    /**
     * 400 Bad Request
     * The requested resource supports the used method, but not for the supplied
     * combination of arguments. More should be provided to deduce the specific action.
     * Header Allow present.
     * Might change into a 405 in the future.
     */
    public static function noAction() {
        http_response_code(400);

        global $methods, $method, $actions;

        $allowed = implode(', ', $methods);
        header("Allow: $allowed");

        $error = "Action could not be deduced from the provided arguments";

        $data = [
            'methods' => $methods,
            'method' => $method,
            'actions' => $actions
        ];

        static::error('No Action', $error, $data);
    }

    /**
     * 400 Bad Request
     * General 400 error with a generic message and data JSON.
     */
    public static function badRequest(string $error, array $data = null) {
        http_response_code(400);

        static::error('Bad Request', $error, $data);
    }

    /**
     * 401 Unauthorized
     * The resource requested is not accessible to the client, or an
     * authentication attempt failed. Required header WWW-Authenticate is present.
     */
    public static function unauthorized(int $userid = null) {
        http_response_code(401);
        $realm = static::$authenticationRealm;
        header("WWW-Authenticate: Basic realm=\"$realm\"");

        if ($userid === null) {
            $error = "Unauthorized request: requires login";

            $data = [];
        } else if (Auth::isAdminUser($userid)) {
            $error = "Unauthorized request: requires administrator login";

            $data = [];
        } else {
            $error = "Unauthorized request: requires login as $userid";

            $data = ['userid' => $userid];
        }

        static::error('Unauthorized', $error, $data);
    }

    /**
     * 403 Forbidden
     * The resource requires privileged (admin) access, so the user cannot access it.
     */
    public static function forbidden() {
        http_response_code(403);

        $error = "Forbidden request: requires privileged access";

        static::error('Forbidden', $error);
    }

    /**
     * 403 Forbidden
     * The request tried to create a resource that conflicted with an already existing
     * resource.
     */
    public static function conflict(string $error, string $entity, string $culprit) {
        http_response_code(403);

        $error = "Conflict: $error";

        $data = [
            'culprit' => $culprit,
            'entity' => $entity
        ];

        static::error('Conflict', $error, $data);
    }

    /**
     * 404 Not Found
     * The resource requested by the client does not exist.
     */
    public static function notFound(string $resource) {
        http_response_code(404);

        $error = "Requested resource does not exist: $resource";

        $data = [
            'resource' => $resource
        ];

        static::error('Not Found', $error, $data);
    }

    /**
     * 404 Not Found
     * The requested action on the resource seems valid, but said action requires
     * the existence of another resource as specified by the client's arguments
     * and that resource does not exist.
     */
    public static function adjacentNotFound(string $adjacent) {
        http_response_code(404);

        $error = "Implied adjacent entity does not exist: $adjacent";

        $data = [
            'resource' => $adjacent,
            'adjacent' => $adjacent
        ];

        static::error('Adjacent Not Found', $error, $data);
    }

    /**
     * 405 Method Not Allowed
     * The requested resource does not support the used method for any combination
     * of arguments.
     * Required header Allow present.
     */
    public static function badMethod() {
        http_response_code(405);

        global $methods, $method, $actions;

        $allowed = implode(', ', $methods);
        header("Allow: $allowed");

        $error = "HTTP request method $method not supported for this resource";

        $data = [
            'methods' => $methods,
            'method' => $method,
            'actions' => $actions
        ];

        static::error('Bad Method', $error, $data);
    }

    /**
     * 500 Internal Server Error
     */
    public static function serverError(array $data = null) {
        http_response_code(500);
        header("Retry-After: 3");

        $error = "Internal Server Error processing the request";

        static::error('Internal Server Error', $error, $data);
    }

    /**
     * 503 Service Unavailable
     * Database schema change (debugging only, presumably...)
     */
    public static function schemaChanged(array $data = null) {
        http_response_code(503);
        header("Retry-After: 1");

        $error = "Database schema changed, try again immediately.";

        static::error('Schema changed', $error, $data);
    }

    /**
     * 503 Service Unavailable
     */
    public static function unavailable(string $message, int $time, array $data = null) {
        http_response_code(503);
        header("Retry-After: $time");

        $error = "Service unavailable: $message";

        static::error('Service Unavailable', $error, $data);
    }
}
?>
