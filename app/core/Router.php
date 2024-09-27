<?php

namespace App\core;

class Router {
    private $routes = [];

    public function add($route, $callback): void
    {
        $this->routes[$route] = $callback;
    }

    public function dispatch($url): void
    {
        if (array_key_exists($url, $this->routes)) {
            call_user_func($this->routes[$url]);
        } else {
            echo "No route found for URL: $url<br>";
        }
    }
}
