<?php
namespace App\models;

use App\core\Model;
use Exception;

class Material extends Model {
    public function uploadMaterial($professorId, $courseId, $fileName, $targetFile, $description, $displayName): int|string
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Determine the types for MySQL binding
            $professorIdType = is_int($professorId) ? 'i' : 's';
            $courseIdType = is_int($courseId) ? 'i' : 's';

            // Insert material into MySQL
            $query = "INSERT INTO course_materials (professor_id, course_id, file_name, path, description, display_name) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param($professorIdType . $courseIdType . 'ssss', $professorId, $courseId, $fileName, $targetFile, $description, $displayName);

            if (!$stmt->execute()) {
                throw new Exception("MySQL insertion error: " . $stmt->error);
            }

            // Get the ID of the inserted material
            $materialId = $stmt->insert_id;

            // Create the PROVIDES_MATERIAL relationship in Neo4j
            $neoQuery = 'MATCH (c:Course {id: $courseId}), (p:User {userId: $professorId, role: "professor"}) CREATE (p)-[:PROVIDES_MATERIAL]->(c)';
            $neoParams = [
                'courseId' => is_int($courseId) ? (int)$courseId : (string)$courseId,
                'professorId' => is_int($professorId) ? (int)$professorId : (string)$professorId
            ];
            $neoResult = $neo4jTx->run($neoQuery, $neoParams);

            // Check if the relationship creation query ran successfully
            if ($neoResult->getSummary()->getCounters()->relationshipsCreated() === 0) {
                throw new Exception("Neo4j relationship creation error: Relationship was not created.");
            }

            // Commit MySQL transaction if all operations are successful
            $this->mysql_conn->commit();
            $neo4jTx->commit();

            return $materialId;
        } catch (Exception $e) {
            // Rollback MySQL transaction on error
            $this->mysql_conn->rollback();

            try {
                $neo4jTx->rollback();
            } catch (Exception $neoEx) {
                echo "Neo4j rollback failed: " . $neoEx->getMessage() . "<br>";
            }

            echo "Transaction failed: " . $e->getMessage() . "<br>";
            return 0; // Indicating failure
        }
    }



    public function getAllMaterialsByProfessor($professorId): array
    {
        $query = "SELECT cm.*, c.name as course_name FROM course_materials cm JOIN courses c ON cm.course_id = c.id WHERE cm.professor_id = ? ORDER BY uploaded_at DESC";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteMaterial($id): bool
    {
        // Start MySQL transaction
        $this->mysql_conn->begin_transaction();

        // Start Neo4j transaction
        $neo4jTx = $this->neo4j_conn->beginTransaction();

        try {
            // Get the file path and material details from MySQL
            $query = "SELECT path, course_id, professor_id FROM course_materials WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $file = $result->fetch_assoc();

            if ($file) {
                // Delete the file from the filesystem
                if (file_exists($file['path'])) {
                    unlink($file['path']);
                }

                // Delete the material record from MySQL
                $query = "DELETE FROM course_materials WHERE id = ?";
                $stmt = $this->mysql_conn->prepare($query);
                $stmt->bind_param("i", $id);
                if (!$stmt->execute()) {
                    throw new Exception("MySQL deletion error: " . $stmt->error);
                }

                // Determine the types for Neo4j parameters
                $courseIdType = is_int($file['course_id']) ? 'i' : 's';
                $professorIdType = is_int($file['professor_id']) ? 'i' : 's';

                // Delete the PROVIDES_MATERIAL relationship from Neo4j
                $neoQuery = 'MATCH (p:User {userId: $professorId, role: "professor"})-[r:PROVIDES_MATERIAL]->(c:Course {id: $courseId}) DELETE r';
                $neoParams = [
                    'courseId' => is_int($file['course_id']) ? (int)$file['course_id'] : (string)$file['course_id'],
                    'professorId' => is_int($file['professor_id']) ? (int)$file['professor_id'] : (string)$file['professor_id']
                ];
                $neoResult = $neo4jTx->run($neoQuery, $neoParams);

                // Check if the relationship deletion query ran successfully
                if ($neoResult->getSummary()->getCounters()->relationshipsDeleted() === 0) {
                    throw new Exception("Neo4j relationship deletion error: Relationship was not deleted.");
                }

                // Commit both transactions if all operations are successful
                $this->mysql_conn->commit();
                $neo4jTx->commit();

                return true;
            } else {
                // Rollback MySQL transaction if no file found
                $this->mysql_conn->rollback();
                return false;
            }
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




    public function getMaterialById($id): false|array|null
    {
        $query = "SELECT * FROM course_materials WHERE id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateMaterial($id, $fileName, $targetFile, $description, $displayName): void
    {
        $query = "UPDATE course_materials SET file_name = ?, path = ?, description = ?, display_name = ? WHERE id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("ssssi", $fileName, $targetFile, $description, $displayName, $id);
        $stmt->execute();
    }

    public function getMaterialsByStudent($studentId): array
    {
        $query = "
        SELECT cm.*, c.name as course_name
        FROM course_materials cm
        JOIN courses c ON cm.course_id = c.id
        JOIN student_enrollments se ON se.course_id = c.id
        WHERE se.student_id = ?
    ";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
