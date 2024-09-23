<?php
// Filename: initialization.php
// Purpose: do the messy bits at the start of the index file

namespace frakturmedia\blither;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// set the cookie and then start the session
session_set_cookie_params(['SameSite' => 'Strict']);
session_start();

require_once("../vendor/autoload.php");

// Composer autoloader for components
require_once("../php/constants.php");

date_default_timezone_set(TIMEZONE);

// load or create ../php/config.php
if ( !file_exists(CONF_FILE) ) {
    copy(CONF_TEMPLATE, CONF_FILE);
}
require_once(CONF_FILE);

// load constants and the main utility functions
require_once '../php/classes/user.php';
require_once('../php/functions.php');

// -------------------------
// log user out if requested
// -------------------------
if (strcmp($req[0], "logout") === 0) {
    // log the user out
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 2592000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // destory session
    session_destroy();
}

// -------------------------
// process logging in and user loading
// -------------------------
$user = new User();

// load user if already logged into session
if (isset($_SESSION['uid'])) {
    // loads all the user attributes into the $user object
    $user->loadFromUid($_SESSION['uid']);
}

/* Some preprocessing before any output is sent back to browser
 * for some specific requests: login, api
 */
switch ($req[0]) {
    case "login":
        // submitting login form - process it here
        if (isset($_POST['inputPassword'])) {
            // check password for this email address
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'inputPassword');

            if ($user->verify($email, $password)) {
                // email/password combo is correct
                // save uid to session and use this to load user in the future
                $_SESSION['uid'] = $user->getId();
            }
        }

        // if logged in - redirect to the browse page of OERs
        if ($user->getStatus() >= MEMBER_STATUS_BASIC) {
            // logged in successfully - redirect to OERs
            header('Location: /account');
        }
        break;

    case 'api':
        require_once '../php/api.php';
        break;

    default:
}


# log errors according to PRODUCTION or DEVELOPMENT
ini_set("log_errors", 1);
ini_set("error_log", "../php-error.log");

// use logging and present errors differently
if (SERVER_IS_PRODUCTION) {
    # load appropriate php configs
    ini_set('display_startup_errors', 0);
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE);
} else {
    # Error diagnostic
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();

    # load appropriate php configs
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Monolog - create log channels
$log = new Logger('main');
$log->pushHandler(new StreamHandler(LOGSDIR . 'main.log', Level::Info));
// Use: $log->info("something"), $log->warning(), or $log->error()

// EOF
