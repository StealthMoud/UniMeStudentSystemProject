<?php

namespace App\models;

use App\core\Model;
use Exception;

class Exam extends Model {
    public function getExamsByProfessor($professorId): array
    {
        $query = "
            SELECT exams.*, courses.name AS course_name
            FROM exams
            INNER JOIN courses ON exams.course_id = courses.id
            WHERE exams.professor_id = ?
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getExamsByLevel($level): array
    {
        $query = "
        SELECT exams.*, courses.name AS course_name
        FROM exams
        INNER JOIN courses ON exams.course_id = courses.id
        WHERE courses.level = ?
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function createExam($courseId, $professorId, $examDate, $location): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Determine the types for MySQL binding
            $courseIdType = is_int($courseId) ? 'i' : 's';
            $professorIdType = is_int($professorId) ? 'i' : 's';

            // Insert exam into MySQL
            $query = "INSERT INTO exams (course_id, professor_id, exam_date, location) VALUES (?, ?, ?, ?)";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param($courseIdType . $professorIdType . "ss", $courseId, $professorId, $examDate, $location);

            if (!$stmt->execute()) {
                throw new Exception("MySQL insertion error: " . $stmt->error);
            }

            // Create the PROVIDES_EXAM relationship in Neo4j
            $neoQuery = 'MATCH (c:Course {id: $courseId}), (p:User {userId: $professorId, role: "professor"}) CREATE (p)-[:PROVIDES_EXAM]->(c)';
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

    public function deleteExam($id): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Get the course_id and professor_id for the exam
            $query = "SELECT course_id, professor_id FROM exams WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $exam = $result->fetch_assoc();

            if (!$exam) {
                throw new Exception("Exam not found: ID " . $id);
            }

            // Delete the exam from MySQL
            $query = "DELETE FROM exams WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("MySQL deletion error: " . $stmt->error);
            }

            // Delete the PROVIDES_EXAM relationship from Neo4j
            $neoQuery = 'MATCH (p:User {userId: $professorId, role: "professor"})-[r:PROVIDES_EXAM]->(c:Course {id: $courseId}) DELETE r';
            $neoParams = [
                'courseId' => is_int($exam['course_id']) ? (int)$exam['course_id'] : (string)$exam['course_id'],
                'professorId' => is_int($exam['professor_id']) ? (int)$exam['professor_id'] : (string)$exam['professor_id']
            ];
            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

            // Check if the relationship deletion query ran successfully
            if ($neoResult->getSummary()->getCounters()->relationshipsDeleted() === 0) {
                throw new Exception("Neo4j relationship deletion error: Relationship was not deleted.");
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



    public function deleteExpiredExams(): \mysqli_result|bool
    {
        $query = "DELETE FROM exams WHERE exam_date < CURDATE()";
        return $this->mysql_conn->query($query);
    }

    public function getExamsByStudent($studentId): array
    {
        $query = "
            SELECT exams.*, courses.name AS course_name, majors.name AS major_name
            FROM exams
            INNER JOIN courses ON exams.course_id = courses.id
            INNER JOIN majors ON courses.major_id = majors.id
            INNER JOIN student_enrollments se ON se.course_id = courses.id
            WHERE se.student_id = ?
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }



}
