<?php

class Session
{
    public static function set($key, $value = null)
    {
        $_SESSION[$key] = $value;
    }

    public static function unset($key)
    {
        unset($_SESSION[$key]);
    }

    public static function get($key)
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }
}