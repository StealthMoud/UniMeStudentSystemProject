<?php
namespace App\models;

use App\Core\Model;

class Material extends Model {
    public function uploadMaterial($professorId, $courseId, $fileName, $targetFile, $description, $displayName) {
        $query = "INSERT INTO course_materials (professor_id, course_id, file_name, path, description, display_name) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iissss", $professorId, $courseId, $fileName, $targetFile, $description, $displayName);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function getAllMaterialsByProfessor($professorId) {
        $query = "SELECT cm.*, c.name as course_name FROM course_materials cm JOIN courses c ON cm.course_id = c.id WHERE cm.professor_id = ? ORDER BY uploaded_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $professorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteMaterial($id) {
        $query = "SELECT path FROM course_materials WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();

        if ($file) {
            if (file_exists($file['path'])) {
                unlink($file['path']);
            }
            $query = "DELETE FROM course_materials WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        return false;
    }

    public function getMaterialById($id) {
        $query = "SELECT * FROM course_materials WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateMaterial($id, $fileName, $targetFile, $description, $displayName) {
        $query = "UPDATE course_materials SET file_name = ?, path = ?, description = ?, display_name = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssssi", $fileName, $targetFile, $description, $displayName, $id);
        $stmt->execute();
    }

    public function getMaterialsByStudent($studentId) {
        $query = "
        SELECT cm.*, c.name as course_name
        FROM course_materials cm
        JOIN courses c ON cm.course_id = c.id
        JOIN student_enrollments se ON se.course_id = c.id
        WHERE se.student_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
