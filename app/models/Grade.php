<?php

namespace App\models;

use App\core\Model;
use Exception;

class Grade extends Model {

    public function enterGrade($studentId, $courseId, $professorId, $grade): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Determine the types for MySQL binding
            $studentIdType = is_int($studentId) ? 'i' : 's';
            $courseIdType = is_int($courseId) ? 'i' : 's';
            $professorIdType = is_int($professorId) ? 'i' : 's';

            // Insert or update grade in MySQL
            $query = "INSERT INTO grades (student_id, course_id, professor_id, grade) VALUES (?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE grade = VALUES(grade)";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param($studentIdType . $courseIdType . $professorIdType . 'i', $studentId, $courseId, $professorId, $grade);

            if (!$stmt->execute()) {
                throw new Exception("MySQL insertion/update error: " . $stmt->error);
            }

            // Create the HAS_GRADE relationship in Neo4j
            $neoQuery = 'MATCH (s:User {userId: $studentId, role: "student"}), (c:Course {id: $courseId}), (p:User {userId: $professorId, role: "professor"})
                     CREATE (s)-[:HAS_GRADE {grade: $grade}]->(c)-[:GIVEN_BY]->(p)';
            $neoParams = [
                'studentId' => is_int($studentId) ? (int)$studentId : (string)$studentId,
                'courseId' => is_int($courseId) ? (int)$courseId : (string)$courseId,
                'professorId' => is_int($professorId) ? (int)$professorId : (string)$professorId,
                'grade' => $grade
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

    public function deleteGrade($studentId, $courseId, $professorId): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Determine the types for MySQL binding
            $studentIdType = is_int($studentId) ? 'i' : 's';
            $courseIdType = is_int($courseId) ? 'i' : 's';
            $professorIdType = is_int($professorId) ? 'i' : 's';

            // Delete grade from MySQL
            $query = "DELETE FROM grades WHERE student_id = ? AND course_id = ? AND professor_id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param($studentIdType . $courseIdType . $professorIdType, $studentId, $courseId, $professorId);

            if (!$stmt->execute()) {
                throw new Exception("MySQL deletion error: " . $stmt->error);
            }

            // Log the parameters and the query for debugging
            echo "Executing Neo4j query with parameters: studentId={$studentId}, courseId={$courseId}, professorId={$professorId}<br>";

            // Convert IDs to appropriate types
            $neoStudentId = is_int($studentId) ? (int)$studentId : (string)$studentId;
            $neoCourseId = is_int($courseId) ? (int)$courseId : (string)$courseId;
            $neoProfessorId = is_int($professorId) ? (int)$professorId : (string)$professorId;

            // Check for the existence of the relationship first (simplified query)
            $checkQuery = 'MATCH (s:User {userId: $studentId, role: "student"})-[r:HAS_GRADE]->(c:Course {id: $courseId})-[g:GIVEN_BY]->(p:User {userId: $professorId, role: "professor"}) RETURN r, g LIMIT 1';
            $checkParams = [
                'studentId' => $neoStudentId,
                'courseId' => $neoCourseId,
                'professorId' => $neoProfessorId
            ];
            $checkResult = $neo4jTx->run($checkQuery, $checkParams);

            if ($checkResult->count() === 0) {
                throw new Exception("Neo4j relationship deletion error: No matching relationships found.");
            }

            // Delete the HAS_GRADE and GIVEN_BY relationships in Neo4j
            $neoQuery = 'MATCH (s:User {userId: $studentId, role: "student"})-[r:HAS_GRADE]->(c:Course {id: $courseId})-[g:GIVEN_BY]->(p:User {userId: $professorId, role: "professor"}) DELETE r, g';
            $neoResult = $neo4jTx->run($neoQuery, $checkParams);

            // Check if the relationship deletion query ran successfully
            if ($neoResult->getSummary()->getCounters()->relationshipsDeleted() === 0) {
                throw new Exception("Neo4j relationship deletion error: Relationship was not deleted.");
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









    public function updateGrade($studentId, $courseId, $professorId, $grade): bool
    {
        $query = "UPDATE grades SET grade = ? WHERE student_id = ? AND course_id = ? AND professor_id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("iiii", $grade, $studentId, $courseId, $professorId);
        return $stmt->execute();
    }


    public function getEnrolledStudents($courseId): array
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

        return $studentDetails;
    }


    /**
     * @throws Exception
     */
    public function fetchGradesByCourse($courseId): array
    {
        // Check if the MySQL connection is still open
        if (!$this->mysql_conn->ping()) {
            throw new Exception("MySQL connection is closed.");
        }

        // Query to fetch grades and user details
        $query = "
        SELECT g.*, u.username, u.email, u.password, c.name AS course_name
        FROM grades g
        JOIN users u ON g.student_id = u.id
        JOIN courses c ON g.course_id = c.id
        WHERE g.course_id = ?
    ";

        $stmt = $this->mysql_conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare MySQL statement: " . $this->mysql_conn->error);
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
            $username = $grade['username'];
            $password = $grade['password'];

            // Extract the base username (before @)
            $baseUsername = explode('@', $username)[0];

            // Get the user_id of the applicant role with the same base username and password
            $applicantUsername = $baseUsername . '@applicant.unime.it';
            $stmt2 = $this->mysql_conn->prepare("SELECT id FROM users WHERE username = ? AND password = ? AND role = 'applicant'");
            $stmt2->bind_param("ss", $applicantUsername, $password);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($row2 = $result2->fetch_assoc()) {
                $applicantId = $row2['id'];
                $applicantCollection = $this->mongo_db->selectCollection('applicants');

                // Get the applicant details from MongoDB
                $applicant = $applicantCollection->findOne(['user_id' => $applicantId]);

                if ($applicant) {
                    if (isset($applicant['additional_info']['name'])) {
                        $grade['student_name'] = $applicant['additional_info']['name'];
                    } else {
                        $grade['student_name'] = 'Name not found';
                        error_log("Applicant found, but 'name' not present in additional_info for user_id: $applicantId");
                    }
                } else {
                    $grade['student_name'] = 'Name not found';
                    error_log("Applicant not found in MongoDB for user_id: $applicantId");
                }
            } else {
                $grade['student_name'] = 'Name not found';
                error_log("Applicant not found in MySQL for username: $applicantUsername");
            }
        }

        return $grades;
    }


    public function getGradesByStudent($studentId): array
    {
        $query = "
        SELECT g.*, c.name AS course_name, u.password AS professor_password
        FROM grades g
        JOIN courses c ON g.course_id = c.id
        JOIN users u ON g.professor_id = u.id
        WHERE g.student_id = ?
    ";
        $stmt = $this->mysql_conn->prepare($query);
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
