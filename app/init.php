<?php
session_start();

// Global variables
$GLOBALS['config'] = array(
    'mysql' => array(
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => '',
        'db'        => ''
    )
);

define('ROOT', dirname(dirname(__FILE__)).'/');
unset($root);
define('URL', "//". $_SERVER['HTTP_HOST'] . "/Camagru/");
define('CURRENT', "//". $_SERVER['HTTP_HOST'] . "/" . substr($_SERVER['REQUEST_URI'], 1));
define('SITE_TITLE', "Camagru");
define('SITE_DESCRIPTION', "Camagru");

// Autoload classes
function __autoload($class) {
    if (file_exists(ROOT . "app/core/{$class}.php")) {
        require_once ROOT . "app/core/{$class}.php";
    }
}