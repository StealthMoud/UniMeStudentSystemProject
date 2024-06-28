<?php

namespace App\models;

use App\Core\Model;
use Exception;

class Course extends Model {
    public function getCoursesByLevel($level) {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            WHERE majors.level = ?
            ORDER BY courses.name
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllCourses() {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            ORDER BY courses.name
        ";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByMajor($majorId) {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            WHERE courses.major_id = ?
            ORDER BY courses.name
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $majorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * @throws Exception
     */
    public function assignProfessorToCourse($courseId, $professorId) {
        // Ensure the professor ID exists in the professors table
        $query = "SELECT COUNT(*) FROM professors WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
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
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $professorId, $courseId);
        return $stmt->execute();
    }




    public function createCourse($name, $major_id, $professor_id, $credits) {
        $query = "INSERT INTO courses (name, major_id, professor_id, credits) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("siii", $name, $major_id, $professor_id, $credits);
        return $stmt->execute();
    }

    public function deleteCourse($id) {
        // First, delete related exams
        $query = "DELETE FROM exams WHERE course_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Now, delete the course
        $query = "DELETE FROM courses WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getEnrolledCourses($studentId) {
        $query = "
            SELECT courses.id, courses.name, majors.name as major_name
            FROM courses
            JOIN enrollments ON courses.id = enrollments.course_id
            JOIN majors ON courses.major_id = majors.id
            WHERE enrollments.student_id = ?
            ORDER BY courses.name
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getApprovedCoursesByProfessor($professorId) {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            JOIN majors ON courses.major_id = majors.id
            JOIN users ON courses.professor_id = users.id
            JOIN course_requests ON courses.id = course_requests.course_id
            WHERE course_requests.professor_id = ? AND course_requests.status = 'approved'
            ORDER BY courses.name
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesWithProfessors() {
        $query = "
            SELECT c.*, m.name AS major_name, u.username AS professor_name
            FROM courses c
            JOIN majors m ON c.major_id = m.id
            LEFT JOIN users u ON c.professor_id = u.id
            WHERE c.professor_id IS NOT NULL
            ORDER BY c.name
        ";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByLevelAndMajorWithProfessor($level, $majorId) {
        $query = "
            SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
            FROM courses
            LEFT JOIN majors ON courses.major_id = majors.id
            LEFT JOIN users ON courses.professor_id = users.id
            WHERE majors.level = ? AND courses.major_id = ? AND courses.professor_id IS NOT NULL
            ORDER BY courses.name
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $level, $majorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByLevelAndMajor($level, $major) {
        $query = "
        SELECT courses.*, majors.name AS major_name, users.username AS professor_name 
        FROM courses
        LEFT JOIN majors ON courses.major_id = majors.id
        LEFT JOIN users ON courses.professor_id = users.id
        WHERE majors.level = ? AND majors.name = ?
        ORDER BY courses.name
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $level, $major);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCoursesByProfessor($professorId) {
        $query = "
            SELECT courses.*, majors.name AS major_name
            FROM courses
            JOIN majors ON courses.major_id = majors.id
            WHERE courses.professor_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }




}
