<?php

namespace App\models;

use App\core\Model;
use Exception;

class Major extends Model {


    public function getMajorsByLevel($level): array
    {
        $query = "SELECT * FROM majors WHERE level = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function getMajorById($majorId): false|array|null
    {
        $stmt = $this->mysql_conn->prepare('SELECT name FROM majors WHERE id = ? AND deleted = 0');
        $stmt->bind_param('i', $majorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $major = $result->fetch_assoc();
        $stmt->close();
        return $major;
    }



    public function createMajor($name, $level): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Insert major into MySQL
            $query = "INSERT INTO majors (name, level) VALUES (?, ?)";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("ss", $name, $level);

            if (!$stmt->execute()) {
                throw new Exception("MySQL insertion error: " . $stmt->error);
            }

            // Get the ID of the inserted major
            $majorId = $stmt->insert_id;

            // Create a node for the major in Neo4j
            $neoQuery = 'CREATE (m:Major {id: $majorId, name: $name, level: $level})';
            $neoParams = [
                'majorId' => (int)$majorId,
                'name' => $name,
                'level' => $level
            ];
            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

            // Check if the node creation query ran successfully
            if ($neoResult->getSummary()->getCounters()->nodesCreated() === 0) {
                throw new Exception("Neo4j node creation error: Node was not created.");
            }

            // Commit both transactions if all operations are successful
            $this->mysql_conn->commit();
            $neo4jTx->commit();

            return true;
        } catch (Exception $e) {
            // Rollback MySQL transaction on error
            $this->mysql_conn->rollback();

            try {
                $neo4jTx->rollback();
            } catch (Exception $neoEx) {
                echo "Neo4j rollback failed: " . $neoEx->getMessage() . "<br>";
            }

            echo "Transaction failed: " . $e->getMessage() . "<br>";
            return false;
        }
    }


    public function deleteMajor($id): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // First, update references in the courses table
            $query = "UPDATE courses SET major_id = NULL WHERE major_id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("MySQL update error: " . $stmt->error);
            }

            // Delete the major from MySQL
            $query = "DELETE FROM majors WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("MySQL deletion error: " . $stmt->error);
            }

            // Detach and delete the BELONGS_TO relationships and the major node in Neo4j
            $neoQuery = 'MATCH (m:Major {id: $majorId}) DETACH DELETE m';
            $neoParams = ['majorId' => (int)$id];
            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

            // Check if the node deletion query ran successfully
            if ($neoResult->getSummary()->getCounters()->nodesDeleted() === 0) {
                throw new Exception("Neo4j deletion error: Node was not deleted.");
            }

            // Commit both transactions if all operations are successful
            $this->mysql_conn->commit();
            $neo4jTx->commit();

            return true;
        } catch (Exception $e) {
            // Rollback MySQL transaction on error
            $this->mysql_conn->rollback();

            try {
                $neo4jTx->rollback();
            } catch (Exception $neoEx) {
                echo "Neo4j rollback failed: " . $neoEx->getMessage() . "<br>";
            }

            echo "Transaction failed: " . $e->getMessage() . "<br>";
            return false;
        }
    }

}
