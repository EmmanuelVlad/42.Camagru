<?php

class Router
{
    private $url;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add($path, $callable, $name, $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if (is_string($callable) && $name === null) {
            $url = explode('/', $callable);
            $name = $url[0];
            if (isset($url[1])) {
                $name .= '/' . $url[1];
            }
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function execute()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new Exception('test');
        }


        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }
    }

    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            return false;
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }
}