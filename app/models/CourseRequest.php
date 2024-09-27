<?php

namespace App\models;

use App\core\Model;
use Exception;

class CourseRequest extends Model {
    public function createCourseRequest($courseId, $professorId): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();
        $status = 'PENDING';

        try {
            // Insert course request into MySQL
            $query = "INSERT INTO course_requests (course_id, professor_id, status) VALUES (?, ?, ?)";
            $stmt = $this->mysql_conn->prepare($query);

            // Determine the types for MySQL bind_param
            $courseIdType = is_int($courseId) ? 'i' : 's';
            $professorIdType = is_int($professorId) ? 'i' : 's';

            $stmt->bind_param("{$courseIdType}{$professorIdType}s", $courseId, $professorId, $status);

            if (!$stmt->execute()) {
                throw new Exception("MySQL insertion error: " . $stmt->error);
            }

            // Validate existence of nodes in Neo4j
            $validationQuery = 'MATCH (c:Course {id: $courseId}), (p:User {userId: $professorId, role: "professor"}) RETURN c, p';
            $validationParams = [
                'courseId' => $courseId,
                'professorId' => $professorId
            ];
            $validationResult = $neo4jTx->run($validationQuery, $validationParams);

            if ($validationResult->count() === 0) {
                throw new Exception("Validation error: Course or Professor not found in Neo4j.");
            }

            // Create the relation with status in Neo4j
            $neoQuery = 'MATCH (c:Course {id: $courseId}), (p:User {userId: $professorId, role: "professor"}) CREATE (c)-[:COURSE_REQUEST {status: $status}]->(p)';
            $neoParams = [
                'courseId' => $courseId,
                'professorId' => $professorId,
                'status' => $status
            ];
            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

            // Check if the relationship creation query ran successfully
            if ($neoResult->getSummary()->getCounters()->relationshipsCreated() === 0) {
                throw new Exception("Neo4j relationship creation error: Relationship was not created.");
            }

            // Commit MySQL transaction if all operations are successful
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




    public function getPendingRequests(): array
    {
        $query = "
            SELECT course_requests.*, courses.name AS course_name, users.username AS professor_name 
            FROM course_requests
            JOIN courses ON course_requests.course_id = courses.id
            JOIN users ON course_requests.professor_id = users.id
            WHERE course_requests.status = 'pending'
        ";
        $result = $this->mysql_conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updateRequestStatus($requestId, $status): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Determine the type for MySQL bind_param for requestId
            $requestIdType = is_int($requestId) ? 'i' : 's';

            // Update the status in MySQL
            $query = "UPDATE course_requests SET status = ? WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("s{$requestIdType}", $status, $requestId);

            if (!$stmt->execute()) {
                throw new Exception("MySQL update error: " . $stmt->error);
            }

            // Fetch course_id and professor_id from course_requests table
            $fetchQuery = "SELECT course_id, professor_id FROM course_requests WHERE id = ?";
            $fetchStmt = $this->mysql_conn->prepare($fetchQuery);
            $fetchStmt->bind_param($requestIdType, $requestId);
            $fetchStmt->execute();
            $fetchStmt->bind_result($courseId, $professorId);
            $fetchStmt->fetch();
            $fetchStmt->close();

            // Update the relation status in Neo4j
            $neoQuery = '
            MATCH (c:Course) WHERE c.id = toString($courseId)
            MATCH (p:User {userId: $professorId, role: "professor"})
            MATCH (c)-[r:COURSE_REQUEST]->(p)
            SET r.status = $status
            RETURN r
        ';
            $neoParams = [
                'courseId' => (string) $courseId,
                'professorId' => $professorId,
                'status' => $status
            ];

            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

            // Check if the relationship update query ran successfully
            if ($neoResult->getSummary()->getCounters()->propertiesSet() === 0) {
                throw new Exception("Neo4j relationship update error: Relationship status was not updated.");
            }

            // Create a new TEACHES relationship in Neo4j only if status is 'approved'
            if ($status === 'approved') {
                $neoCreateQuery = '
                MATCH (c:Course) WHERE c.id = toString($courseId)
                MATCH (p:User {userId: $professorId, role: "professor"})
                MERGE (p)-[:TEACHES]->(c)
            ';
                $neoCreateParams = [
                    'courseId' => (string) $courseId,
                    'professorId' => $professorId
                ];

                $neoCreateResult = $neo4jTx->run($neoCreateQuery, $neoCreateParams);

                // Check if the TEACHES relationship was created successfully
                if ($neoCreateResult->getSummary()->getCounters()->relationshipsCreated() === 0) {
                    throw new Exception("Neo4j relationship creation error: TEACHES relationship was not created.");
                }
            }

            // Commit MySQL transaction if all operations are successful
            $this->mysql_conn->commit();
            $neo4jTx->commit();

            return true;
        } catch (Exception $e) {
            // Rollback MySQL transaction on error
            $this->mysql_conn->rollback();

            try {
                $neo4jTx->rollback();
            } catch (Exception $neoEx) {
                // Handle Neo4j rollback failure
            }

            return false;
        }
    }




    public function getRequestById($requestId): false|array|null
    {
        $query = "
        SELECT * FROM course_requests
        WHERE id = ?
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public function getRequestsByProfessor($professorId): array
    {
        $query = "
        SELECT course_requests.*, courses.name AS course_name
        FROM course_requests
        JOIN courses ON course_requests.course_id = courses.id
        WHERE course_requests.professor_id = ?
        ORDER BY course_requests.created_at DESC
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllRequests(): array
    {
        $query = "
        SELECT course_requests.*, courses.name AS course_name, users.username AS professor_name 
        FROM course_requests
        JOIN courses ON course_requests.course_id = courses.id
        JOIN users ON course_requests.professor_id = users.id
        ORDER BY course_requests.created_at DESC
    ";
        $result = $this->mysql_conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }



}
