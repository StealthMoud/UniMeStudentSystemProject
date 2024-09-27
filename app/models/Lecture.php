<?php

namespace App\models;

use App\core\Model;
use Exception;

class Lecture extends Model {
    public function getAllLecturesWithMajors(): array
    {
        $query = "
            SELECT lectures.*, courses.name as course_name, majors.name as major_name
            FROM lectures
            JOIN courses ON lectures.course_id = courses.id
            JOIN majors ON courses.major_id = majors.id
        ";
        $result = $this->mysql_conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function scheduleLecture($courseId, $professorId, $title, $description, $date): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Determine the types for MySQL binding
            $courseIdType = is_int($courseId) ? 'i' : 's';
            $professorIdType = is_int($professorId) ? 'i' : 's';

            // Insert lecture into MySQL
            $query = "INSERT INTO lectures (course_id, professor_id, title, description, scheduled_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param($courseIdType . $professorIdType . 'sss', $courseId, $professorId, $title, $description, $date);

            if (!$stmt->execute()) {
                throw new Exception("MySQL insertion error: " . $stmt->error);
            }

            // Create the TEACHES relationship in Neo4j
            $neoQuery = 'MATCH (c:Course {id: $courseId}), (p:User {userId: $professorId, role: "professor"}) CREATE (p)-[:SCHEDULED_LECTURE]->(c)';
            $neoParams = [
                'courseId' => is_int($courseId) ? (int)$courseId : (string)$courseId,
                'professorId' => is_int($professorId) ? (int)$professorId : (string)$professorId
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



    public function deleteLecture($lectureId): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Retrieve the course_id and professor_id associated with the lecture
            $selectQuery = "SELECT course_id, professor_id FROM lectures WHERE id = ?";
            $selectStmt = $this->mysql_conn->prepare($selectQuery);
            $selectStmt->bind_param("i", $lectureId);
            $selectStmt->execute();
            $selectStmt->bind_result($courseId, $professorId);
            $selectStmt->fetch();
            $selectStmt->close();

            if (empty($courseId) || empty($professorId)) {
                throw new Exception("Lecture not found or missing course_id/professor_id");
            }

            // Delete the lecture from MySQL
            $deleteQuery = "DELETE FROM lectures WHERE id = ?";
            $deleteStmt = $this->mysql_conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $lectureId);

            if (!$deleteStmt->execute()) {
                throw new Exception("MySQL deletion error: " . $deleteStmt->error);
            }
            $deleteStmt->close();

            // Determine the types for Neo4j parameters
            $courseIdType = is_int($courseId) ? 'i' : 's';
            $professorIdType = is_int($professorId) ? 'i' : 's';

            // Detach and delete the TEACHES relationship in Neo4j
            $neoQuery = 'MATCH (p:User {userId: $professorId, role: "professor"})-[r:TEACHES]->(c:Course {id: $courseId}) DELETE r';
            $neoParams = [
                'courseId' => is_int($courseId) ? (int)$courseId : (string)$courseId,
                'professorId' => is_int($professorId) ? (int)$professorId : (string)$professorId
            ];
            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

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



    public function getLecturesByProfessor($professorId): array
    {
        $query = "
        SELECT lectures.*, courses.name as course_name, majors.name as major_name
        FROM lectures
        JOIN courses ON lectures.course_id = courses.id
        JOIN majors ON courses.major_id = majors.id
        WHERE lectures.professor_id = ?
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getScheduledLecturesByStudent($studentId): array
    {
        $query = "
        SELECT lectures.*, courses.name as course_name, majors.name as major_name
        FROM lectures
        JOIN courses ON lectures.course_id = courses.id
        JOIN majors ON courses.major_id = majors.id
        JOIN student_enrollments se ON se.course_id = courses.id
        WHERE se.student_id = ?
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
