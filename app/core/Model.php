<?php

namespace App\core;

use App\Config\database;

require_once '../app/config/database.php';

class Model {
    protected $db;

    public function __construct() {
        $database = new database();
        $this->db = $database->getConnection();
    }
}
