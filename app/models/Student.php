<?php

namespace App\models;

use App\core\Model;

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
        $stmt = $this->mysql_conn->prepare($query);
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

    public function getStudentDetails($userId): array
    {
        // Connect to MongoDB collection
        $applicantCollection = $this->mongo_db->selectCollection('applicants');

        // Find the applicant document by the given user ID
        $applicant = $applicantCollection->findOne(['user_id' => (int)$userId], ['projection' => ['additional_info.education_level' => 1, 'additional_info.major' => 1]]);

        // Fetch the education level and major
        $level = $applicant['additional_info']['education_level'] ?? null;
        $major = $applicant['additional_info']['major'] ?? null;


        return ['level' => $level, 'major' => $major];
    }


    public function getApplicantDetails($userId)
    {
        // Connect to MongoDB collection
        $applicantCollection = $this->mongo_db->selectCollection('applicants');

        // Find the applicant document by the given user ID
        $applicant = $applicantCollection->findOne(['user_id' => (int)$userId], ['projection' => ['additional_info' => 1]]);

        // Check if additional_info is not null before decoding
        return $applicant['additional_info'] ?? [];
    }


    private function getUserPassword($userId) {
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($password);
        $stmt->fetch();
        $stmt->close();

        return $password;
    }

    private function isApplicant($userId): bool
    {
        // Connect to MongoDB collection
        $applicantCollection = $this->mongo_db->selectCollection('applicants');

        // Count the number of documents with the given user ID
        $count = $applicantCollection->countDocuments(['user_id' => (int)$userId]);

        return $count > 0;
    }


    private function getMajorNameById($majorId) {
        $query = "SELECT name FROM majors WHERE id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $majorId);
        $stmt->execute();
        $stmt->bind_result($majorName);
        $stmt->fetch();
        $stmt->close();
        return $majorName;
    }


    function getStudentsByCourse($courseId): array
    {

        // Step 1: Get all student_id from student_enrollments where course_id = ?
        $stmt = $this->mysql_conn->prepare("SELECT student_id FROM student_enrollments WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();

        $studentDetails = [];

        while ($row = $result->fetch_assoc()) {
            $studentId = $row['student_id'];

            // Step 2: Get the username and password of the student role from users table
            $stmt2 = $this->mysql_conn->prepare("SELECT username, password FROM users WHERE id = ? AND role = 'student'");
            $stmt2->bind_param("i", $studentId);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($row2 = $result2->fetch_assoc()) {
                $username = $row2['username'];
                $password = $row2['password'];

                // Extract the base username (before @)
                $baseUsername = explode('@', $username)[0];

                // Step 3: Get the user_id of the applicant role with the same base username and password
                $applicantUsername = $baseUsername . '@applicant.unime.it';
                $stmt3 = $this->mysql_conn->prepare("SELECT id FROM users WHERE username = ? AND password = ? AND role = 'applicant'");
                $stmt3->bind_param("ss", $applicantUsername, $password);
                $stmt3->execute();
                $result3 = $stmt3->get_result();

                if ($row3 = $result3->fetch_assoc()) {
                    $applicantId = $row3['id'];
                    $applicantCollection = $this->mongo_db->selectCollection('applicants');

                    // Step 4: Get the applicant details from MongoDB
                    $applicant = $applicantCollection->findOne(['user_id' => $applicantId]);

                    if ($applicant) {
                        $studentDetails[] = [
                            'student_id' => $studentId,
                            'name' => $applicant['additional_info']['name'],
                            'email' => $applicant['additional_info']['email'],
                            'address' => $applicant['additional_info']['address'],
                            'previous_education' => $applicant['additional_info']['previous_education'],
                            'grades' => $applicant['additional_info']['grades'],
                            'education_level' => $applicant['additional_info']['education_level'],
                            'major' => $applicant['additional_info']['major']
                        ];
                    }
                }
            }
        }

        // Close the connections
        $stmt->close();
        $stmt2->close();
        $stmt3->close();

        return $studentDetails;
    }


    public function getApplicantUserIdByPassword($password)
    {
        // Connect to MongoDB collection
        $usersCollection = $this->mongo_db->selectCollection('users');
        $applicantCollection = $this->mongo_db->selectCollection('applicants');

        // Find the user document by the given password
        $user = $usersCollection->findOne(['password' => $password], ['projection' => ['id' => 1]]);

        // Check if user exists and fetch the applicant user ID
        if ($user) {
            $applicant = $applicantCollection->findOne(['user_id' => (int)$user['id']], ['projection' => ['user_id' => 1]]);
            return $applicant['user_id'] ?? null;
        }

        return null;
    }




}
