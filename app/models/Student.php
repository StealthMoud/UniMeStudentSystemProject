<?php

namespace App\models;

use App\Core\Model;

class Student extends Model {

    public function getApplicantUserIdByStudentUserId($studentUserId) {
        // Fetch the password of the student
        $password = $this->getUserPassword($studentUserId);

        // Use the password to find the corresponding applicant user ID
        $query = "
            SELECT id
            FROM users
            WHERE password = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $password);
        $stmt->execute();
        $stmt->bind_result($userId);

        $userIds = [];
        while ($stmt->fetch()) {
            $userIds[] = $userId;
        }
        $stmt->close();

        $applicantUserId = null;

        // Loop through results to find the applicant user ID
        foreach ($userIds as $userId) {
            if ($this->isApplicant($userId)) {
                $applicantUserId = $userId;
                break;
            }
        }

        return $applicantUserId;
    }

    public function getStudentDetails($userId) {
        $query = "
            SELECT 
                JSON_UNQUOTE(JSON_EXTRACT(additional_info, '$.education_level')) as level,
                JSON_UNQUOTE(JSON_EXTRACT(additional_info, '$.major')) as major
            FROM applicants
            WHERE user_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($level, $major);
        $stmt->fetch();
        $stmt->close();

        // Fetch the major name directly
        $majorName = $this->getMajorNameById($major);

        return ['level' => $level, 'major' => $majorName];
    }

    public function getApplicantDetails($userId) {
        $query = "
            SELECT additional_info
            FROM applicants
            WHERE user_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($additionalInfo);
        $stmt->fetch();
        $stmt->close();

        // Check if additional_info is not null before decoding
        return $additionalInfo ? json_decode($additionalInfo, true) : [];
    }

    private function getUserPassword($userId) {
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($password);
        $stmt->fetch();
        $stmt->close();

        return $password;
    }

    private function isApplicant($userId) {
        $query = "
            SELECT COUNT(*)
            FROM applicants
            WHERE user_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    private function getMajorNameById($majorId) {
        $query = "SELECT name FROM majors WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $majorId);
        $stmt->execute();
        $stmt->bind_result($majorName);
        $stmt->fetch();
        $stmt->close();
        return $majorName;
    }

    public function getStudentsByCourse($courseId) {
        // Fetch student enrollments for the given course
        $enrollmentQuery = "
        SELECT se.student_id
        FROM student_enrollments se
        WHERE se.course_id = ?
    ";
        $stmt = $this->db->prepare($enrollmentQuery);
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();

        $enrollments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $students = [];

        foreach ($enrollments as $enrollment) {
            $studentId = $enrollment['student_id'];
            $password = $this->getUserPassword($studentId);
            $applicantUserId = $this->getApplicantUserIdByPassword($password);

            if ($applicantUserId) {
                $applicantQuery = "
                SELECT JSON_UNQUOTE(JSON_EXTRACT(a.additional_info, '$.name')) AS name
                FROM applicants a
                WHERE a.user_id = ?
            ";
                $stmt = $this->db->prepare($applicantQuery);
                $stmt->bind_param("i", $applicantUserId);
                $stmt->execute();
                $stmt->bind_result($name);
                while ($stmt->fetch()) {
                    $students[] = ['id' => $studentId, 'name' => $name];
                }
                $stmt->close();
            }
        }

        return $students;
    }


    public function getApplicantUserIdByPassword($password) {
        $query = "
        SELECT user_id
        FROM applicants
        WHERE user_id = (SELECT id FROM users WHERE password = ? LIMIT 1)
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $password);
        $stmt->execute();
        $stmt->bind_result($applicantUserId);
        $stmt->fetch();
        $stmt->close();

        return $applicantUserId;
    }




}
