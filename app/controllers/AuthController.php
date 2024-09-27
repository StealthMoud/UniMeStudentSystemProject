<?php

namespace App\controllers;

use App\core\Controller;
use App\models\User;
use App\utilities\Session;
use App\utilities\Validator;

class AuthController extends Controller {

    public function register(): void
    {
        Session::init();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = Validator::sanitize($_POST['username']);
            $password = Validator::sanitize($_POST['password']);
            $role = $_POST['role'] ?? 'applicant'; // Default role
            $isAdminAction = isset($_POST['role']);

            if (!Validator::validateUsername($username, $role)) {
                $error = 'Username must follow the pattern: username@' . $role . '.unime.it';
                if ($isAdminAction) {
                    header('Location: ' . BASE_URL . '?url=admin/manage_users&error=' . urlencode($error));
                } else {
                    $this->view('auth/register', ['error' => $error]);
                }
                return;
            }

            if (!Validator::validatePassword($password)) {
                $error = 'Password must be at least 8 characters long and include letters, numbers, and special characters.';
                if ($isAdminAction) {
                    header('Location: ' . BASE_URL . '?url=admin/manage_users&error=' . urlencode($error));
                } else {
                    $this->view('auth/register', ['error' => $error]);
                }
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $email = $role . '_' . explode('@', $username)[0] . '@unime.it';

            $user = new User();

            // Attempt to create the user
            $result = $user->createUser($username, $email, $hashedPassword, $role);

            if (isset($result['success'])) {
                Session::set('user_id', $result['success']);
                Session::set('role', $role);

                if ($isAdminAction) {
                    header('Location: ' . BASE_URL . '?url=admin/manage_users&message=User created successfully');
                } else {
                    $this->view('auth/register_success');
                }
            } else {
                if ($isAdminAction) {
                    header('Location: ' . BASE_URL . '?url=admin/manage_users&error=' . urlencode($result['error']));
                } else {
                    $this->view('auth/register', ['error' => $result['error']]);
                }
            }
        } else {
            $this->view('auth/register');
        }
    }

    public function login(): void
    {
        Session::init();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = Validator::sanitize($_POST['username']);
            $password = Validator::sanitize($_POST['password']);
            $rolePart = explode('@', $username)[1] ?? '';

            // Determine the role based on the username's domain part
            $role = match ($rolePart) {
                'admin.unime.it' => 'admin',
                'professor.unime.it' => 'professor',
                'student.unime.it' => 'student',
                'applicant.unime.it' => 'applicant',
                default => '',
            };

            $user = $this->model('User');
            $authenticatedUser = $user->authenticate($username, $password);

            // Check if authentication was successful and role matches
            if ($authenticatedUser && $authenticatedUser['role'] === $role) {
                Session::set('user_id', $authenticatedUser['id']);
                Session::set('role', $authenticatedUser['role']);

                switch ($role) {
                    case 'admin':
                        header('Location: ' . BASE_URL . '?url=admin/dashboard');
                        break;
                    case 'professor':
                        header('Location: ' . BASE_URL . '?url=professor/dashboard');
                        break;
                    case 'student':
                        header('Location: ' . BASE_URL . '?url=student/dashboard');
                        break;
                    case 'applicant':
                        header('Location: ' . BASE_URL . '?url=applicant/dashboard');
                        break;
                    default:
                        $this->view('auth/login', ['error' => 'Invalid role']);
                        break;
                }
                exit; // Ensure no further code is executed after redirection
            } else {
                // If authentication fails, show the login page with an error
                $this->view('auth/login', ['error' => 'Invalid credentials or role mismatch']);
            }
        } else {
            // If not a POST request, just show the login page
            $this->view('auth/login');
        }
    }

    public function logout(): void
    {
        Session::destroy();
        header('Location: ' . BASE_URL); // Redirect to base URL
    }
}
