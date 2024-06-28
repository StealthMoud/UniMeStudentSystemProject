<?php

namespace App\models;

use App\Core\Model;

class Lecture extends Model {
    public function getAllLecturesWithMajors() {
        $query = "
            SELECT lectures.*, courses.name as course_name, majors.name as major_name
            FROM lectures
            JOIN courses ON lectures.course_id = courses.id
            JOIN majors ON courses.major_id = majors.id
        ";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function scheduleLecture($courseId, $professorId, $title, $description, $date) {
        $query = "INSERT INTO lectures (course_id, professor_id, title, description, scheduled_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iisss", $courseId, $professorId, $title, $description, $date);
        $stmt->execute();
    }

    public function deleteLecture($lectureId) {
        $query = "DELETE FROM lectures WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $lectureId);
        $stmt->execute();
        $stmt->close();
    }

    public function getLecturesByProfessor($professorId) {
        $query = "
        SELECT lectures.*, courses.name as course_name, majors.name as major_name
        FROM lectures
        JOIN courses ON lectures.course_id = courses.id
        JOIN majors ON courses.major_id = majors.id
        WHERE lectures.professor_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getScheduledLecturesByStudent($studentId) {
        $query = "
        SELECT lectures.*, courses.name as course_name, majors.name as major_name
        FROM lectures
        JOIN courses ON lectures.course_id = courses.id
        JOIN majors ON courses.major_id = majors.id
        JOIN student_enrollments se ON se.course_id = courses.id
        WHERE se.student_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
