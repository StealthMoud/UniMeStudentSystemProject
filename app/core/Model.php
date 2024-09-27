<?php

namespace App\core;

use App\config\Database;
use Exception;
use mysqli;
use MongoDB\Client as MongoDBClient;
use Laudis\Neo4j\Contracts\ClientInterface;

class Model {
    protected ?mysqli $mysql_conn;
    protected ?MongoDBClient $mongo_conn;
    protected ?ClientInterface $neo4j_conn;
    protected ?\MongoDB\Database $mongo_db;

    /**
     * @throws Exception
     */
    public function __construct() {
        $database = new Database();
        $this->mysql_conn = $database->getMysqlConnection();
        $this->mongo_conn = $database->getMongoConnection();
        $this->neo4j_conn = $database->getNeo4jConnection();
        $this->mongo_db = $database->getMongoDatabase();
    }
}
