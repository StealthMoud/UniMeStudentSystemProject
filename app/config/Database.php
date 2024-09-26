<?php

namespace App\config;

class Database {
    private $mysql_host = DB_HOST;
    private $mysql_db_name = DB_NAME;
    private $mysql_username = DB_USER;
    private $mysql_password = DB_PASS;

    private $mongo_host = 'mongo-db';
    private $mongo_port = 27017;

    private $neo4j_host = 'neo4j-db';
    private $neo4j_port = 7687;
    private $neo4j_user = 'neo4j';
    private $neo4j_password = '';

    public $mysql_conn;
    public $mongo_conn;
    public $neo4j_conn;

    public function getMysqlConnection() {
        $this->mysql_conn = null;
        try {
            $this->mysql_conn = new \mysqli($this->mysql_host, $this->mysql_username, $this->mysql_password, $this->mysql_db_name);
            if ($this->mysql_conn->connect_error) {
                throw new \Exception("MySQL Connection failed: " . $this->mysql_conn->connect_error);
            }
        } catch (\Exception $e) {
            echo "MySQL Connection error: " . $e->getMessage();
        }

        return $this->mysql_conn;
    }

    public function getMongoConnection() {
        $this->mongo_conn = null;
        try {
            $manager = new \MongoDB\Driver\Manager("mongodb://{$this->mongo_host}:{$this->mongo_port}");
            $this->mongo_conn = $manager;
        } catch (\Exception $e) {
            echo "MongoDB Connection error: " . $e->getMessage();
        }

        return $this->mongo_conn;
    }

    public function getNeo4jConnection() {
        $this->neo4j_conn = null;
        try {
            $client = \GraphAware\Neo4j\Client\ClientBuilder::create()
                ->addConnection('default', "bolt://{$this->neo4j_user}:{$this->neo4j_password}@{$this->neo4j_host}:{$this->neo4j_port}")
                ->build();
            $this->neo4j_conn = $client;
        } catch (\Exception $e) {
            echo "Neo4j Connection error: " . $e->getMessage();
        }

        return $this->neo4j_conn;
    }

}
