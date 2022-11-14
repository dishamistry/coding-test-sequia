<?php

namespace application\core;

class Router
{
    public $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file)
    {
        $router = new static;
        require_once $file;
        return $router;
    }

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            return $this->callAction(
                ...explode('@', $this->routes[$requestType][$uri])
            );
        }
        throw new \Exception("no routes defined for this uri");
    }

    protected function callAction($controller, $action)
    {
        $controller = "application\\controllers\\{$controller}";
        $controller = new $controller;
        if (!method_exists($controller, $action)) {
            throw new \Exception("{$controller} does not respond to the  {$action} action");
        }
        return $controller->$action();
    }
}