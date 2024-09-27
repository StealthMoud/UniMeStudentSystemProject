<?php

namespace App\models;

use App\core\Model;
use Exception;

class Course extends Model {
    public function getCoursesByLevel($level): array
    {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            WHERE majors.level = ?
            ORDER BY courses.name
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllCourses(): array
    {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            ORDER BY courses.name
        ";
        $result = $this->mysql_conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByMajor($majorId): array
    {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            WHERE courses.major_id = ?
            ORDER BY courses.name
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $majorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * @throws Exception
     */
    public function assignProfessorToCourse($courseId, $professorId): bool
    {
        // Ensure the professor ID exists in the professors table
        $query = "SELECT COUNT(*) FROM professors WHERE user_id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        // Debugging: Output the count
        error_log("Professor count for ID $professorId: " . $count);

        if ($count == 0) {
            throw new Exception("Invalid professor ID: $professorId");
        }

        // Proceed with the update if professor ID is valid
        $query = "UPDATE courses SET professor_id = ? WHERE id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("ii", $professorId, $courseId);
        return $stmt->execute();
    }


    public function createCourse($name, $major_id, $professor_id, $credits, $level): bool
    {
        // Cast professor_id to int at the beginning
        $professor_id = (int) $professor_id;

        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Insert course into MySQL
            $insertQuery = "INSERT INTO courses (name, major_id, professor_id, credits, level) VALUES (?, ?, ?, ?, ?)";
            $insertStmt = $this->mysql_conn->prepare($insertQuery);
            $insertStmt->bind_param("siiis", $name, $major_id, $professor_id, $credits, $level);

            if (!$insertStmt->execute()) {
                throw new Exception("MySQL insertion error: " . $insertStmt->error);
            }

            // Get the ID of the inserted course
            $course_id = $insertStmt->insert_id;

            // Insert course node into Neo4j
            $neoQuery = 'CREATE (c:Course {id: $id, name: $name, level: $level, credits: $credits})';
            $neoParams = [
                'id' => $course_id,
                'name' => $name,
                'level' => $level,
                'credits' => $credits,
            ];
            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

            // Check if the course node was created
            if ($neoResult->getSummary()->getCounters()->nodesCreated() === 0) {
                throw new Exception("Neo4j insertion error: Node was not created.");
            }

            // Create relationship with major
            $relQuery = 'MATCH (c:Course {id: $course_id}), (m:Major {id: $major_id}) CREATE (c)-[:BELONGS_TO]->(m)';
            $relParams = [
                'course_id' => $course_id,
                'major_id' => $major_id
            ];
            $relResult = $neo4jTx->run($relQuery, $relParams);

            // Check if the relationship with major was created
            if ($relResult->getSummary()->getCounters()->relationshipsCreated() === 0) {
                throw new Exception("Neo4j relationship creation error with Major: Relationship was not created.");
            }

            // Create relationship with professor if professor_id is not null
            if (!empty($professor_id)) {
                // Check if the professor node exists before creating the relationship
                $checkProfessorQuery = 'MATCH (p:User {userId: $professor_id, role: "professor"}) RETURN p';
                $checkProfessorParams = [
                    'professor_id' => $professor_id
                ];
                $checkProfessorResult = $neo4jTx->run($checkProfessorQuery, $checkProfessorParams);

                if ($checkProfessorResult->count() == 0) {
                    throw new Exception("Professor node does not exist");
                }

                // Create the TAUGHT_BY relationship
                $profRelQuery = 'MATCH (c:Course {id: $course_id}), (p:User {userId: $professor_id, role: "professor"}) CREATE (c)-[:TAUGHT_BY]->(p)';
                $profRelParams = [
                    'course_id' => $course_id,
                    'professor_id' => $professor_id
                ];
                $profRelResult = $neo4jTx->run($profRelQuery, $profRelParams);

                // Check if the relationship creation query ran successfully
                if ($profRelResult->getSummary()->getCounters()->relationshipsCreated() === 0) {
                    throw new Exception("Neo4j relationship creation error with Professor: Relationship was not created.");
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
                echo "Neo4j rollback failed: " . $neoEx->getMessage() . "<br>";
            }

            echo "Transaction failed: " . $e->getMessage() . "<br>";
            return false;
        }
    }




    public function deleteCourse($id): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // First, delete related exams from MySQL
            $query = "DELETE FROM exams WHERE course_id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("MySQL deletion error (exams): " . $stmt->error);
            }

            // Delete the course from MySQL
            $query = "DELETE FROM courses WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("MySQL deletion error (course): " . $stmt->error);
            }

            // Detach and delete the course node and all its relationships in Neo4j
            $neoQuery = 'MATCH (c:Course {id: $courseId}) DETACH DELETE c';
            $neoParams = ['courseId' => (int)$id];
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


    public function getEnrolledCourses($studentId): array
    {
        $query = "
            SELECT courses.id, courses.name, majors.name as major_name
            FROM courses
            JOIN enrollments ON courses.id = enrollments.course_id
            JOIN majors ON courses.major_id = majors.id
            WHERE enrollments.student_id = ?
            ORDER BY courses.name
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

//    public function getApprovedCoursesByProfessor($professorId): array
//    {
//        $query = "
//            SELECT courses.*, majors.name AS major_name, users.username AS professor_name
//            FROM courses
//            JOIN majors ON courses.major_id = majors.id
//            JOIN users ON courses.professor_id = users.id
//            JOIN course_requests ON courses.id = course_requests.course_id
//            WHERE course_requests.professor_id = ? AND course_requests.status = 'approved'
//            ORDER BY courses.name
//        ";
//        $stmt = $this->mysql_conn->prepare($query);
//        $stmt->bind_param("i", $professorId);
//        $stmt->execute();
//        $result = $stmt->get_result();
//        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
//    }

    public function getApprovedAndAssignedCoursesByProfessor($professorId): array
    {
        $query = "
        SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
        FROM courses
        JOIN majors ON courses.major_id = majors.id
        JOIN users ON courses.professor_id = users.id
        LEFT JOIN course_requests ON courses.id = course_requests.course_id AND course_requests.status = 'approved'
        WHERE courses.professor_id = ? OR course_requests.professor_id = ?
        GROUP BY courses.id,courses.name
        ORDER BY courses.name
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("ii", $professorId, $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function getCoursesWithProfessors(): array
    {
        $query = "
            SELECT c.*, m.name AS major_name, u.username AS professor_name
            FROM courses c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN users u ON c.professor_id = u.id
            WHERE c.professor_id IS NOT NULL
            ORDER BY c.name
        ";
        $result = $this->mysql_conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByLevelAndMajorWithProfessor($level, $majorId): array
    {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            WHERE majors.level = ? AND courses.major_id = ? AND courses.professor_id IS NOT NULL
            ORDER BY courses.name
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("si", $level, $majorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByLevelAndMajor($level, $major): array
    {
        $query = "
        SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
        FROM courses
        LEFT JOIN majors ON courses.major_id = majors.id
        LEFT JOIN users ON courses.professor_id = users.id
        WHERE majors.level = ? AND majors.name = ?
        ORDER BY courses.name
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("ss", $level, $major);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByProfessor($professorId): array
    {
        $query = "
            SELECT courses.*, majors.name AS major_name
            FROM courses
            JOIN majors ON courses.major_id = majors.id
            WHERE courses.professor_id = ?
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }




}
