<?php

namespace App\models;

use App\Core\Model;

class Grade extends Model {
    public function getGradesByCourse($courseId) {
        $query = "
            SELECT grades.*, JSON_UNQUOTE(JSON_EXTRACT(a.additional_info, '$.name')) AS student_name
            FROM grades
            JOIN users u ON grades.student_id = u.id
            JOIN applicants a ON u.id = a.user_id
            WHERE grades.course_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getEnrolledStudents($courseId) {
        $query = "
        SELECT s.user_id as student_id, u.password as student_password
        FROM student_enrollments se
        JOIN students s ON se.student_id = s.user_id
        JOIN users u ON s.user_id = u.id
        WHERE se.course_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $students = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // Get applicant details using the password
        foreach ($students as &$student) {
            $student['applicant'] = $this->getApplicantDetailsByPassword($student['student_password']);
        }

        return $students;
    }

    private function getApplicantDetailsByPassword($password) {
        $query = "
        SELECT JSON_UNQUOTE(JSON_EXTRACT(a.additional_info, '$.name')) AS name
        FROM applicants a
        JOIN users u ON a.user_id = u.id
        WHERE u.password = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }


    public function enterGrade($studentId, $courseId, $professorId, $grade) {
        $query = "INSERT INTO grades (student_id, course_id, professor_id, grade) VALUES (?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE grade = VALUES(grade)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiii", $studentId, $courseId, $professorId, $grade);
        return $stmt->execute();
    }

    public function fetchGradesByCourse($courseId) {
        // Query to fetch grades and user details
        $query = "
        SELECT g.*, u.username, u.email, u.password, c.name AS course_name
        FROM grades g
        JOIN users u ON g.student_id = u.id
        JOIN courses c ON g.course_id = c.id
        WHERE g.course_id = ?
    ";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            return [];
        }

        $grades = $result->fetch_all(MYSQLI_ASSOC);

        // Fetch the student names using their passwords
        foreach ($grades as &$grade) {
            $applicantDetails = $this->getApplicantDetailsByPassword($grade['password']);
            if ($applicantDetails) {
                $grade['student_name'] = $applicantDetails['name'];
            } else {
                $grade['student_name'] = 'Name not found';
            }
        }

        return $grades;
    }

    public function updateGrade($studentId, $courseId, $professorId, $grade) {
        $query = "UPDATE grades SET grade = ? WHERE student_id = ? AND course_id = ? AND professor_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiii", $grade, $studentId, $courseId, $professorId);
        return $stmt->execute();
    }

    public function deleteGrade($studentId, $courseId, $professorId) {
        $query = "DELETE FROM grades WHERE student_id = ? AND course_id = ? AND professor_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $studentId, $courseId, $professorId);
        return $stmt->execute();
    }

    public function getGradesByStudent($studentId) {
        $query = "
        SELECT g.*, c.name AS course_name, u.password AS professor_password
        FROM grades g
        JOIN courses c ON g.course_id = c.id
        JOIN users u ON g.professor_id = u.id
        WHERE g.student_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        $grades = [];
        while ($row = $result->fetch_assoc()) {
            $professorDetails = $this->getApplicantDetailsByPassword($row['professor_password']);
            $row['professor_name'] = $professorDetails['name'] ?? 'Name not found';
            $grades[] = $row;
        }
        return $grades;
    }



}
