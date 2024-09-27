<?php

namespace App\test;

require_once '../config/Database.php';
require_once '../core/Model.php';

use App\core\Model;

class Neo4jTest extends Model {
    public function insertData(): void
    {
        $neo4j = $this->neo4j_conn;

        if ($neo4j === null) {
            echo "Neo4j connection failed.";
            return;
        }

        try {
            $query = "CREATE (n:TestNode {name: 'Test'}) RETURN n";
            $result = $neo4j->run($query);
            echo "Data inserted successfully: " . print_r($result->getRecord(), true);
        } catch (\Exception $e) {
            echo "Error inserting data: " . $e->getMessage();
        }
    }
}

// Instantiate the Neo4jTest class and call the insertData method
$test = new Neo4jTest();
$test->insertData();
