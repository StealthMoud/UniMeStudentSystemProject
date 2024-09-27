<?php

namespace App\models;

use App\core\Model;
use App\utilities\Session;
use Exception;

// Suppress deprecation warnings temporarily
error_reporting(E_ALL & ~E_DEPRECATED);

class User extends Model {
    
    public function getAllUsers(): array
    {
        $query = "SELECT * FROM users WHERE deleted = FALSE";
        $result = $this->mysql_conn->query($query);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }


    public function getUsersByRole($role): array
    {
        $query = "SELECT * FROM users WHERE role = ? AND deleted = FALSE";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function createUser($username, $email, $password, $role): array
    {
        try {
            // Start MySQL transaction
            $this->mysql_conn->begin_transaction();

            // Check if the username already exists (excluding soft-deleted users)
            $query = "SELECT * FROM users WHERE username = ? AND deleted = FALSE";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingUser = $result->fetch_assoc();

            if ($existingUser) {
                // Username exists and is active
                $this->mysql_conn->rollback(); // Rollback MySQL transaction
                return ['error' => 'Username already taken.'];
            }

            // Check if the email already exists
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingEmail = $result->fetch_assoc();

            if ($existingEmail) {
                // Email exists
                $this->mysql_conn->rollback(); // Rollback MySQL transaction
                return ['error' => 'Email already taken.'];
            }

            // Create a new user in MySQL
            $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("ssss", $username, $email, $password, $role);
            if ($stmt->execute()) {
                $userId = $stmt->insert_id;

                // Insert into role-specific table (only if applicable)
                $roleInsertSuccess = true;
                switch ($role) {
                    case 'applicant':
                        $collection = $this->mongo_db->selectCollection('applicants');
                        $applicantData = [
                            'user_id' => (int)$userId,
                            'additional_info' => [
                                'name' => '', // example valid data
                                'email' => '', // example valid data
                                'address' => '', // example valid data
                                'previous_education' => '', // example valid data
                                'grades' => 'A', // example valid data
                                'education_level' => '', // example valid data
                                'major' => '' // example valid data
                            ]
                        ];
                        try {
                            $result = $collection->insertOne($applicantData);
                            if (!$result->isAcknowledged()) {
                                $roleInsertSuccess = false;
                            }
                        } catch (\MongoDB\Exception\InvalidArgumentException $e) {
                            $this->mysql_conn->rollback(); // Rollback MySQL transaction
                            return ['error' => 'MongoDB validation error: ' . $e->getMessage()];
                        } catch (\Exception $e) {
                            $this->mysql_conn->rollback(); // Rollback MySQL transaction
                            return ['error' => 'MongoDB error: ' . $e->getMessage()];
                        }
                        break;



                    case 'student':
                        $query = "INSERT INTO students (user_id) VALUES (?)";
                        $stmt = $this->mysql_conn->prepare($query);
                        $stmt->bind_param("i", $userId);
                        if (!$stmt->execute()) {
                            $roleInsertSuccess = false;
                        }
                        break;
                    case 'professor':
                        $query = "INSERT INTO professors (user_id) VALUES (?)";
                        $stmt = $this->mysql_conn->prepare($query);
                        $stmt->bind_param("i", $userId);
                        if (!$stmt->execute()) {
                            $roleInsertSuccess = false;
                        }
                        break;
                    case 'admin':
                        // No specific table insertion needed for admins
                        break;
                    default:
                        $this->mysql_conn->rollback(); // Rollback MySQL transaction
                        return ['error' => 'Invalid role specified.'];
                }

                if (!$roleInsertSuccess) {
                    $this->mysql_conn->rollback(); // Rollback MySQL transaction
                    return ['error' => 'Failed to insert role-specific data.'];
                }

                // Create a user node in Neo4j
                try {
                    if (!$this->neo4j_conn) {
                        throw new Exception('Neo4j connection is not initialized');
                    }

                    $statement = 'CREATE (u:User {userId: $userId, username: $username, email: $email, role: $role})';
                    $parameters = [
                        'userId' => $userId,
                        'username' => $username,
                        'email' => $email,
                        'role' => $role
                    ];

                    $result = $this->neo4j_conn->run($statement, $parameters);

                    // Commit MySQL transaction if Neo4j operation succeeds
                    $this->mysql_conn->commit();
                    return ['success' => $userId]; // Return the new user's ID
                } catch (Exception $e) {
                    $this->mysql_conn->rollback(); // Rollback MySQL transaction
                    return ['error' => 'Failed to create user in Neo4j: ' . $e->getMessage()];
                }
            } else {
                $this->mysql_conn->rollback(); // Rollback MySQL transaction
                return ['error' => 'Failed to create user in MySQL.'];
            }
        } catch (\Exception $e) {
            $this->mysql_conn->rollback(); // Ensure rollback if any exception occurs
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }




    public function authenticate($username, $password): false|array
    {
        $query = "SELECT * FROM users WHERE username = ? AND deleted = FALSE";
        $stmt = $this->mysql_conn->prepare($query);
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




    public function assignRole($userId, $role): bool
    {
        $query = "UPDATE users SET role = ? WHERE id = ? AND deleted = FALSE";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("si", $role, $userId);
        return $stmt->execute();
    }




    public function updateUserRoleAndUsername($userId, $newRole, $newUsername): bool
    {
        $query = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("ssi", $newUsername, $newRole, $userId);
        return $stmt->execute();
    }

    public function getUserById($id): false|array|null
    {
        $query = "SELECT * FROM users WHERE id = ? AND deleted = FALSE";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteUser($id): array
    {
        // Retrieve the current admin ID from the session
        Session::init();
        $currentAdminId = Session::get('user_id');

        if ($id === $currentAdminId) {
            return ['success' => false, 'message' => 'Admins cannot delete their own accounts.'];
        }

        try {
            // Start MySQL transaction
            $this->mysql_conn->begin_transaction();

            // Fetch the user's current data
            $query = "SELECT * FROM users WHERE id = ? AND deleted = FALSE";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                return ['success' => false, 'message' => 'User not found or already deleted.'];
            }

            $role = $user['role'];

            // Delete role-specific data
            $mongoSuccess = true;
            switch ($role) {
                case 'applicant':
                    $collection = $this->mongo_db->selectCollection('applicants');
                    $result = $collection->deleteOne(['user_id' => (int)$id]);
                    if (!$result->isAcknowledged()) {
                        $mongoSuccess = false;
                    }
                    break;
                case 'student':
                    $query = "DELETE FROM students WHERE user_id = ?";
                    $stmt = $this->mysql_conn->prepare($query);
                    $stmt->bind_param("i", $id);
                    if (!$stmt->execute()) {
                        $mongoSuccess = false;
                    }
                    break;
                case 'professor':
                    $query = "DELETE FROM professors WHERE user_id = ?";
                    $stmt = $this->mysql_conn->prepare($query);
                    $stmt->bind_param("i", $id);
                    if (!$stmt->execute()) {
                        $mongoSuccess = false;
                    }
                    break;
                case 'admin':
                    // No specific table deletion needed for admins
                    break;
                default:
                    return ['success' => false, 'message' => 'Invalid role specified.'];

            }

            if (!$mongoSuccess) {
                return ['success' => false, 'message' => 'Failed to delete role-specific data.'];
            }

            // Soft delete the user from MySQL
            $deletedUsername = $user['username'] . '_deleted_' . time();
            $query = "UPDATE users SET username = ?, deleted = TRUE, deleted_at = NOW() WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($query);
            $stmt->bind_param("si", $deletedUsername, $id);
            if (!$stmt->execute()) {
                return ['success' => false, 'message' => 'Failed to soft delete user.'];
            }

            // Delete the user node in Neo4j
            $statement = 'MATCH (u:User {userId: $userId}) DETACH DELETE u';
            $parameters = ['userId' => $id];
            $result = $this->neo4j_conn->run($statement, $parameters);

            if ($result->getSummary()->getCounters()->nodesDeleted() === 0) {
                return ['success' => false, 'message' => "Admins cannot delete their own accounts. Failed to delete user in Neo4j."];
            }

            // Commit transactions
            $this->mysql_conn->commit();
            return ['success' => true, 'message' => 'User deleted successfully.'];
        } catch (Exception $e) {
            $this->mysql_conn->rollback();
            return ['success' => false, 'message' => 'Failed to delete user: ' . $e->getMessage()];
        }
    }







    public function updateUserRole($userId, $role): bool
    {
        $query = "UPDATE users SET role = ? WHERE id = ? AND deleted = FALSE";
        $stmt = $this->mysql_conn->prepare($query);
        $stmt->bind_param("si", $role, $userId);
        return $stmt->execute();
    }



    public function isUsernameExists($username): false|string
    {
        $query = "SELECT id, deleted_at FROM users WHERE username = ?";
        $stmt = $this->mysql_conn->prepare($query);
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
