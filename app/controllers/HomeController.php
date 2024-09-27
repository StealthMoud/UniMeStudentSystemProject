<?php

namespace App\controllers;

use app\core\Controller;

require_once '../app/core/Controller.php';

class HomeController extends Controller {
    public function index(): void
    {
        $this->view('home/index');
    }

    public function about(): void
    {
        $this->view('home/about');
    }

    public function contact(): void
    {
        $this->view('home/contact');
    }
}
