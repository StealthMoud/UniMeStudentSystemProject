<?php

namespace App\models;

use App\Core\Model;

class User extends Model {
    public function getAllUsers() {
        $query = "SELECT * FROM users WHERE deleted = FALSE";
        $result = $this->db->query($query);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = ? AND deleted = FALSE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUsersByRole($role): array
    {
        $query = "SELECT * FROM users WHERE role = ? AND deleted = FALSE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function assignRole($userId, $role): bool
    {
        $query = "UPDATE users SET role = ? WHERE id = ? AND deleted = FALSE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $role, $userId);
        return $stmt->execute();
    }

    public function createUser($username, $email, $password, $role): array
    {
        // Check if the username already exists
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingUser = $result->fetch_assoc();

        if ($existingUser) {
            if ($existingUser['deleted']) {
                // Username exists and is soft-deleted
                return ['error' => 'This username was previously registered and cannot be reused.'];
            } else {
                // Username exists and is active
                return ['error' => 'Username already taken.'];
            }
        }

        // Create a new user
        $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            $userId = $stmt->insert_id;

            // Insert into role-specific table
            switch ($role) {
                case 'applicant':
                    $query = "INSERT INTO applicants (user_id) VALUES (?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bind_param("i", $userId);
                    break;
                case 'student':
                    $query = "INSERT INTO students (user_id) VALUES (?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bind_param("i", $userId);
                    break;
                case 'professor':
                    $query = "INSERT INTO professors (user_id) VALUES (?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bind_param("i", $userId);
                    break;
                default:
                    return ['error' => 'Invalid role specified.'];
            }

            if ($stmt->execute()) {
                return ['success' => $userId]; // Return the new user's ID
            } else {
                return ['error' => 'Failed to insert user into role-specific table.'];
            }
        }
        return ['error' => 'Failed to create user.'];
    }






    public function updateUserRoleAndUsername($userId, $newRole, $newUsername) {
        $query = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $newUsername, $newRole, $userId);
        return $stmt->execute();
    }



    public function deleteUser($id) {
        // Fetch the user's current data
        $user = $this->getUserById($id);

        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        // Prevent self-deletion for non-applicants
        if ($id == $_SESSION['user_id'] && $user['role'] !== 'applicant') {
            return ['success' => false, 'message' => 'Admins cannot delete their own accounts.'];
        }

        // Append a timestamp to the username to mark it as deleted
        $deletedUsername = $user['username'];
        $query = "UPDATE users SET username = ?, deleted = TRUE, deleted_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $deletedUsername, $id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User deleted successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete user.'];
        }
    }




    public function updateUserRole($userId, $role) {
        $query = "UPDATE users SET role = ? WHERE id = ? AND deleted = FALSE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $role, $userId);
        return $stmt->execute();
    }

    public function authenticate($username, $password) {
        $query = "SELECT * FROM users WHERE username = ? AND deleted = FALSE";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

    public function isUsernameExists($username) {
        $query = "SELECT id, deleted_at FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if ($user['deleted_at'] !== null) {
                // The username exists and was soft-deleted
                return 'soft_deleted';
            } else {
                // The username exists and is active
                return 'active';
            }
        }
        return false; // Username does not exist
    }
}
