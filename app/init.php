<?php
session_start();

define('ROOT', dirname(dirname(__FILE__)).'/');
unset($root);
define('URL', "//". $_SERVER['HTTP_HOST'] . "/camagru-v2/");
define('CURRENT', "//". $_SERVER['HTTP_HOST'] . "/" . substr($_SERVER['REQUEST_URI'], 1));
define('SITE_TITLE', "Camagru");
define('SITE_DESCRIPTION', "Camagru");

require ROOT . "config/database.php";
require ROOT . "app/core/Functions.php";

// Global variables
$GLOBALS['config'] = array(
    'mysql' => array(
        'dsn'      => $DB_DSN,
        'username'  => $DB_USER,
        'password'  => $DB_PASSWORD
    )
);

// Autoload classes
function __autoload($class) {
    if (file_exists(ROOT . "app/core/{$class}.php")) {
        require_once ROOT . "app/core/{$class}.php";
    }
}