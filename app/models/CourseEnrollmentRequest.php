<?php
namespace App\models;

use App\core\Model;
use Exception;

class CourseEnrollmentRequest extends Model {

    public function createCourseEnrollmentRequest($courseId, $studentId): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Determine the types for MySQL binding
            $courseIdType = is_int($courseId) ? 'i' : 's';
            $studentIdType = is_int($studentId) ? 'i' : 's';

            // Insert course enrollment request into MySQL
            $query = "INSERT INTO course_enrollment_requests (course_id, student_id, status) VALUES (?, ?, 'pending')";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param($courseIdType . $studentIdType, $courseId, $studentId);

            if (!$stmt->execute()) {
                throw new Exception("MySQL insertion error: " . $stmt->error);
            }

            // Create the ENROLLED_IN relationship in Neo4j
            $neoQuery = 'MATCH (c:Course {id: $courseId}), (s:User {userId: $studentId, role: "student"}) CREATE (s)-[:ENROLLED_IN]->(c)';
            $neoParams = [
                'courseId' => is_int($courseId) ? (int)$courseId : (string)$courseId,
                'studentId' => is_int($studentId) ? (int)$studentId : (string)$studentId
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


    public function getEnrolledCourses($studentId): array
    {
        $query = "
            SELECT courses.*, majors.name AS major_name
            FROM courses
            JOIN majors ON courses.major_id = majors.id
            JOIN course_enrollment_requests ON courses.id = course_enrollment_requests.course_id
            JOIN student_enrollments ON courses.id = student_enrollments.course_id
            WHERE student_enrollments.student_id = ? AND course_enrollment_requests.status = 'approved'
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllCourseEnrollmentRequests(): array
    {
        $query = "
            SELECT course_enrollment_requests.*, courses.name as course_name, users.username as student_name
            FROM course_enrollment_requests
            JOIN courses ON course_enrollment_requests.course_id = courses.id
            JOIN students ON course_enrollment_requests.student_id = students.user_id
            JOIN users ON students.user_id = users.id
            ORDER BY course_enrollment_requests.created_at DESC
        ";
        $result = $this->mysql_conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updateCourseEnrollmentRequestStatus($requestId, $status): bool
    {
        $query = "UPDATE course_enrollment_requests SET status = ? WHERE id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("si", $status, $requestId);
        return $stmt->execute();
    }

    public function addStudentToCourse($courseId, $studentId): bool
    {
        $query = "INSERT INTO student_enrollments (student_id, course_id) VALUES (?, ?)";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("ii", $studentId, $courseId);
        return $stmt->execute();
    }

    public function getRequestById($requestId): false|array|null
    {
        $query = "
            SELECT *
            FROM course_enrollment_requests
            WHERE id = ?
        ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public function getRequestByCourseAndStudent($courseId, $studentId): false|array|null
    {
        $query = "
        SELECT *
        FROM course_enrollment_requests
        WHERE course_id = ? AND student_id = ? AND (status = 'pending' OR status = 'approved')
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("ii", $courseId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public function getPendingRequestsByStudent($studentId): array
    {
        $query = "
        SELECT courses.*, majors.name AS major_name, course_enrollment_requests.status
        FROM courses
        JOIN majors ON courses.major_id = majors.id
        JOIN course_enrollment_requests ON courses.id = course_enrollment_requests.course_id
        WHERE course_enrollment_requests.student_id = ? AND course_enrollment_requests.status = 'pending'
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }





}
