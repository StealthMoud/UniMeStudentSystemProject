<?php

namespace App\core;
class View {
    public static function render($view, $data = []) {
        // Extract $data so it's available as variables in the view
        extract($data);

        $viewPath = '../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View file not found: " . $viewPath;
        }
    }
}
