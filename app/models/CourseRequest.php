<?php

namespace App\models;

use App\Core\Model;

class CourseRequest extends Model {
    public function createCourseRequest($courseId, $professorId) {
        $query = "INSERT INTO course_requests (course_id, professor_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $courseId, $professorId);
        return $stmt->execute();
    }

    public function getPendingRequests() {
        $query = "
            SELECT course_requests.*, courses.name AS course_name, users.username AS professor_name 
            FROM course_requests
            JOIN courses ON course_requests.course_id = courses.id
            JOIN users ON course_requests.professor_id = users.id
            WHERE course_requests.status = 'pending'
        ";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updateRequestStatus($requestId, $status) {
        $query = "UPDATE course_requests SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $status, $requestId);
        return $stmt->execute();
    }

    public function getRequestById($requestId) {
        $query = "
        SELECT * FROM course_requests
        WHERE id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public function getRequestsByProfessor($professorId) {
        $query = "
        SELECT course_requests.*, courses.name AS course_name
        FROM course_requests
        JOIN courses ON course_requests.course_id = courses.id
        WHERE course_requests.professor_id = ?
        ORDER BY course_requests.created_at DESC
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllRequests() {
        $query = "
        SELECT course_requests.*, courses.name AS course_name, users.username AS professor_name 
        FROM course_requests
        JOIN courses ON course_requests.course_id = courses.id
        JOIN users ON course_requests.professor_id = users.id
        ORDER BY course_requests.created_at DESC
    ";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }



}
