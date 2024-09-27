<?php

namespace App\core;

use App\utilities\Session;
use Exception;

class Controller {
    public function model($model) {
        try {
            // Construct the model file path
            $modelPath = __DIR__ . '/../models/' . $model . '.php';

            // Check if the model file exists
            if (!file_exists($modelPath)) {
                throw new Exception("Model file not found: " . $modelPath);
            }

            // Require the model file
            require_once $modelPath;

            // Construct the fully qualified model class name
            $modelClass = 'App\\models\\' . $model;

            // Check if the model class exists
            if (!class_exists($modelClass)) {
                throw new Exception("Model class not found: " . $modelClass);
            }

            // Instantiate the model class
            return new $modelClass();

        } catch (Exception $e) {
            // Log the error message (consider using a logger in a real application)
            error_log("Error loading model: " . $e->getMessage());

            // Optionally, display a user-friendly error message
            echo "An error occurred while loading the model. Please try again later.";
            return null;
        }
    }



    public function view($view, $data = []): void
    {
        try {
            $viewPath = __DIR__ . '/../views/' . $view . '.php';

            if (!file_exists($viewPath)) {
                throw new Exception("View file not found: " . $viewPath);
            }

            extract($data);

            require_once $viewPath;
        } catch (Exception $e) {
            echo "Error loading view: " . $e->getMessage();
        }
    }

    protected function authorize($role): void
    {
        Session::init();
        if (Session::get('role') !== $role) {
            header('Location: ' . BASE_URL . '?url=auth/login');
            exit();
        }
    }


}
