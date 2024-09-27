<?php

namespace App\config;

use Exception;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\ClientInterface;
use MongoDB\Client as MongoDBClient;
use mysqli;


class Database {
    private string $mysql_host = DB_HOST;
    private string $mysql_db_name = DB_NAME;
    private string $mysql_username = DB_USER;
    private string $mysql_password = DB_PASS;

    private string $mongo_host = MONGO_HOST;
    private int $mongo_port = MONGO_PORT;
    private string $mongo_db_name = MONGO_DB_NAME; // Add MongoDB database name

    private string $neo4j_host = NEO4J_HOST;
    private int $neo4j_port = NEO4J_PORT;
    private string $neo4j_user = NEO4J_USER;
    private string $neo4j_password = NEO4J_PASS;

    public ?mysqli $mysql_conn = null;
    public ?MongoDBClient $mongo_conn = null;
    public ?ClientInterface $neo4j_conn = null;

    public function getMysqlConnection(): ?mysqli {
        try {
            $this->mysql_conn = new mysqli($this->mysql_host, $this->mysql_username, $this->mysql_password, $this->mysql_db_name);
            if ($this->mysql_conn->connect_error) {
                throw new Exception("MySQL Connection failed: " . $this->mysql_conn->connect_error);
            }
        } catch (Exception $e) {
            echo "MySQL Connection error: " . $e->getMessage();
        }

        return $this->mysql_conn;
    }

    /*public function getMongoConnection(): ?MongoDBClient {
        try {
            $uri = "mongodb://$this->mongo_host:$this->mongo_port";
            $this->mongo_conn = new MongoDBClient($uri);
        } catch (Exception $e) {
            echo "MongoDB Connection error: " . $e->getMessage();
        }

        return $this->mongo_conn;
    }

    public function getMongoDatabase(): ?\MongoDB\Database
    {

        return $this->mongo_conn?->selectDatabase($this->mongo_db_name);
    }*/

    public function getMongoConnection(): ?MongoDBClient {
        try {
            $uri = "mongodb://$this->mongo_host:$this->mongo_port";
            $this->mongo_conn = new MongoDBClient($uri);
        } catch (Exception $e) {
            echo "MongoDB Connection error: " . $e->getMessage() . "\n"; // Echo statement for failed connection
        }

        return $this->mongo_conn;
    }

    public function getMongoDatabase(): ?\MongoDB\Database
    {
        try {
            $database = $this->mongo_conn?->selectDatabase($this->mongo_db_name);
        } catch (Exception $e) {
            echo "MongoDB Database selection error: " . $e->getMessage() . "\n"; // Echo statement for failed database selection
            return null;
        }

        return $database;
    }


    /**
     * @throws Exception
     */
    public function getNeo4jConnection(): ?ClientInterface {
        try {
            $this->neo4j_conn = ClientBuilder::create()
                ->withDriver('bolt', "bolt://{$this->neo4j_user}:{$this->neo4j_password}@{$this->neo4j_host}:{$this->neo4j_port}")
                ->build();
        } catch (Exception $e) {
            throw new Exception("Neo4j Connection error: " . $e->getMessage());
        }

        return $this->neo4j_conn;
    }


}
