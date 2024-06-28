<?php

namespace App\models;

use App\Core\Model;

class Exam extends Model {
    public function getExamsByProfessor($professorId) {
        $query = "
            SELECT exams.*, courses.name AS course_name
            FROM exams
            INNER JOIN courses ON exams.course_id = courses.id
            WHERE exams.professor_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function createExam($courseId, $professorId, $examDate, $location) {
        $query = "INSERT INTO exams (course_id, professor_id, exam_date, location) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiss", $courseId, $professorId, $examDate, $location);
        return $stmt->execute();
    }

    public function deleteExam($id) {
        $query = "DELETE FROM exams WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function deleteExpiredExams() {
        $query = "DELETE FROM exams WHERE exam_date < CURDATE()";
        return $this->db->query($query);
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
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentId);
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
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

}
