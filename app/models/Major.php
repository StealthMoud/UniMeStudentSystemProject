<?php

namespace App\models;

use App\Core\Model;


class Major extends Model {
    public function getMajorsByLevel($level) {
        $query = "SELECT * FROM majors WHERE level = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function createMajor($name, $level) {
        $query = "INSERT INTO majors (name, level) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $name, $level);
        return $stmt->execute();
    }

    public function deleteMajor($id) {
        // First, update references in the courses table
        $query = "UPDATE courses SET major_id = NULL WHERE major_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Now, delete the major
        $query = "DELETE FROM majors WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
