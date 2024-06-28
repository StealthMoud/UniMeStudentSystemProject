<?php
namespace App\models;

use App\Core\Model;

class CourseEnrollmentRequest extends Model {

    public function createCourseEnrollmentRequest($courseId, $studentId) {
        $query = "INSERT INTO course_enrollment_requests (course_id, student_id, status) VALUES (?, ?, 'pending')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $courseId, $studentId);
        return $stmt->execute();
    }

    public function getEnrolledCourses($studentId) {
        $query = "
            SELECT courses.*, majors.name AS major_name
            FROM courses
            JOIN majors ON courses.major_id = majors.id
            JOIN course_enrollment_requests ON courses.id = course_enrollment_requests.course_id
            JOIN student_enrollments ON courses.id = student_enrollments.course_id
            WHERE student_enrollments.student_id = ? AND course_enrollment_requests.status = 'approved'
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllCourseEnrollmentRequests() {
        $query = "
            SELECT course_enrollment_requests.*, courses.name as course_name, users.username as student_name
            FROM course_enrollment_requests
            JOIN courses ON course_enrollment_requests.course_id = courses.id
            JOIN students ON course_enrollment_requests.student_id = students.user_id
            JOIN users ON students.user_id = users.id
            ORDER BY course_enrollment_requests.created_at DESC
        ";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updateCourseEnrollmentRequestStatus($requestId, $status) {
        $query = "UPDATE course_enrollment_requests SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $status, $requestId);
        return $stmt->execute();
    }

    public function addStudentToCourse($courseId, $studentId) {
        $query = "INSERT INTO student_enrollments (student_id, course_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $studentId, $courseId);
        return $stmt->execute();
    }

    public function getRequestById($requestId) {
        $query = "
            SELECT *
            FROM course_enrollment_requests
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public function getRequestByCourseAndStudent($courseId, $studentId) {
        $query = "
        SELECT *
        FROM course_enrollment_requests
        WHERE course_id = ? AND student_id = ? AND (status = 'pending' OR status = 'approved')
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $courseId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public function getPendingRequestsByStudent($studentId) {
        $query = "
        SELECT courses.*, majors.name AS major_name, course_enrollment_requests.status
        FROM courses
        JOIN majors ON courses.major_id = majors.id
        JOIN course_enrollment_requests ON courses.id = course_enrollment_requests.course_id
        WHERE course_enrollment_requests.student_id = ? AND course_enrollment_requests.status = 'pending'
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }





}
