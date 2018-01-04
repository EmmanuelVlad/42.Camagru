<?php

class Route
{
    private $controller = 'home';
    private $method = 'index';

    private $path;
    private $callable;

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function match($url)
    {
        $url = trim($url, '/');
        
        $path = $this->path;

        // Custom capture, format: <:var_name|regex>
        $path = preg_replace('/\<\:(.*?)\|(.*?)\>/', '(?P<\1>\2)', $path);

        // Alphanumeric capture (0-9A-Za-z-_), format: <:var_name>
        $path = preg_replace('/\<\:(.*?)\>/', '(?P<\1>[A-Za-z0-9\-\_]+)', $path);

        // Numeric capture (0-9), format: <#var_name>
        $path = preg_replace('/\<\#(.*?)\>/', '(?P<\1>[0-9]+)', $path);

        // Wildcard capture (Anything INCLUDING directory separators), format: <*var_name>
        $path = preg_replace('/\<\*(.*?)\>/', '(?P<\1>.+)', $path);

        // Wildcard capture (Anything EXCLUDING directory separators), format: <!var_name>
        $path = preg_replace('/\<\!(.*?)\>/', '(?P<\1>[^\/]+)', $path);

        // Add the regular expression syntax to make sure we do a full match or no match
        $regex = "#^$path$#";
        
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        $matches = array_filter($matches, 'is_numeric', ARRAY_FILTER_USE_KEY);        
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    public function call()
    {
        if (is_string($this->callable)) {
            $url = explode('/', $this->callable);
            
            if (file_exists(ROOT . "app/controllers/{$url[0]}.php")) {
                $this->controller = $url[0];
            }

            require_once ROOT . "app/controllers/{$this->controller}.php";

            $this->controller = new $this->controller;

            if (isset($url[1])) {
                if (method_exists($this->controller, $url[1])) {
                    $this->method = $url[1];
                }
            }

            return call_user_func_array([$this->controller, $this->method], array_values($this->matches));
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    public function getUrl($params)
    {
        $path = $this->path;
        foreach($params as $key => $value) {
            $path = preg_replace("/\<(.*?)$key(.*?)\>/", $value, $path);
        }

        return $path;
    }
}