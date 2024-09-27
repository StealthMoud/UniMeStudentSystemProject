<?php

namespace App\controllers;

use App\core\Controller;
use App\utilities\Session;
use App\utilities\Validator;
use JetBrains\PhpStorm\NoReturn;

require_once '../app/core/Controller.php';

class AdminController extends Controller {
    public function dashboard(): void
    {
        Session::init();
        $this->authorize('admin');
        $this->view('admin/dashboard');
    }

    // Bachelor and Master Majors Management
    public function manageBachelorMajors(): void
    {
        $this->manageMajors('bachelor');
    }

    public function manageMasterMajors(): void
    {
        $this->manageMajors('master');
    }

    private function manageMajors($level): void
    {
        $majorModel = $this->model('Major');
        if ($majorModel) {
            $majors = $majorModel->getMajorsByLevel($level);
            $this->view('admin/manage_majors', ['majors' => $majors, 'level' => ucfirst($level)]);
        } else {
            echo "Failed to load the Major model.";
        }
    }

    public function addBachelorMajor(): void
    {
        $this->addMajor('bachelor');
    }

    public function addMasterMajor(): void
    {
        $this->addMajor('master');
    }

    private function addMajor($level): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $majorModel = $this->model('Major');
            $majorName = $_POST['major_name'];
            $majorModel->createMajor($majorName, $level);
        }
        header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_majors');
    }

    public function deleteBachelorMajor(): void
    {
        $this->deleteMajor('bachelor');
    }

    public function deleteMasterMajor(): void
    {
        $this->deleteMajor('master');
    }

    private function deleteMajor($level): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $majorModel = $this->model('Major');
            $majorId = $_POST['major_id'];
            $majorModel->deleteMajor($majorId);
        }
        header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_majors');
    }

    // Bachelor and Master Courses Management
    public function manageBachelorCourses(): void
    {
        $this->manageCourses('bachelor');
    }

    public function manageMasterCourses(): void
    {
        $this->manageCourses('master');
    }

    private function manageCourses($level): void
    {
        $courseModel = $this->model('Course');
        $majorModel = $this->model('Major');
        $userModel = $this->model('User');
        $data['courses'] = $courseModel->getCoursesByLevel($level);
        $data['majors'] = $majorModel->getMajorsByLevel($level);
        $data['professors'] = $userModel->getUsersByRole('professor');
        $data['level'] = ucfirst($level);
        $this->view('admin/manage_courses', $data);
    }

    public function addBachelorCourse(): void
    {
        $this->addCourse('bachelor');
    }

    public function addMasterCourse(): void
    {
        $this->addCourse('master');
    }

    private function addCourse($level): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseModel = $this->model('Course');
            $courseName = $_POST['course_name'];
            $majorId = $_POST['major_id'];
            $professorId = $_POST['professor_id'] ?? null;
            $credits = $_POST['credits'];
            if ($professorId == '') {
                $professorId = NULL;
            }
            $success = $courseModel->createCourse($courseName, $majorId, $professorId, $credits, $level);

            if ($success) {
                header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_courses');
            } else {
                // Handle failure case, e.g., show an error message
                echo "Failed to add course.";
            }
        }
    }


    public function deleteBachelorCourse(): void
    {
        $this->deleteCourse('bachelor');
    }

    public function deleteMasterCourse(): void
    {
        $this->deleteCourse('master');
    }

    private function deleteCourse($level): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseModel = $this->model('Course');
            $courseId =  $_POST['course_id'];
            $courseModel->deleteCourse($courseId);
        }
        header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_courses');
    }

    // User Management
    public function manageUsers(): void
    {
        $users = $this->model('User')->getAllUsers();
        $this->view('admin/manage_users', ['users' => $users]);
    }


    public function addUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and validate input
            $username = Validator::sanitize($_POST['username']);
            $password = Validator::sanitize($_POST['password']);
            $role = $_POST['role'];

            // Create an instance of AuthController and call the register method
            $authController = new AuthController();
            $_POST['role'] = $role; // Set the role in the POST data
            $authController->register();

            // Redirect to the manage users page
            header('Location: ' . BASE_URL . '?url=admin/manage_users');
        } else {
            // Display the add user form if the request is not a POST
            $this->view('admin/manage_users');
        }
    }


    public function deleteUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'];

            $user = $this->model('User');
            $result = $user->deleteUser($userId);

            if ($result['success']) {
                header('Location: ' . BASE_URL . '?url=admin/manage_users&message=' . urlencode($result['message']));
            } else {
                header('Location: ' . BASE_URL . '?url=admin/manage_users&error=' . urlencode($result['message']));
            }
        } else {
            header('Location: ' . BASE_URL . '?url=admin/manage_users');
        }
    }

    // Enrollment Requests Management
    public function viewEnrollmentRequests(): void
    {
        Session::init();
        $this->authorize('admin');

        $studentEnrollmentModel = $this->model('StudentEnrollment');
        $data['enrollmentRequests'] = $studentEnrollmentModel->getAllApplicantRequests();

        $this->view('admin/view_enrollment_requests', $data);
    }


    #[NoReturn] public function approveEnrollmentRequest(): void
    {
        Session::init();
        $this->authorize('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['request_id'];
            $confirmApproval = isset($_POST['confirm_approval']) && $_POST['confirm_approval'] === 'yes';
            $studentEnrollmentModel = $this->model('StudentEnrollment');

            // Get the current status of the enrollment request
            $currentStatus = $studentEnrollmentModel->getRequestStatus($id);

            if ($currentStatus !== 'approved') {
                $applicant = $studentEnrollmentModel->getApplicantByRequestId($id);
                if ($applicant) {
                    $userModel = $this->model('User');
                    $studentUsername = str_replace('@applicant.unime.it', '@student.unime.it', $applicant['username']);
                    $hashedPassword = $applicant['password'];

                    if ($currentStatus === 'pending' || ($currentStatus === 'rejected' && $confirmApproval)) {
                        $usernameExists = $userModel->isUsernameExists($studentUsername);
                        if ($usernameExists === false || $usernameExists === 'soft_deleted') {
                            // Create a new student user
                            $createUserResult = $userModel->createUser($studentUsername, 'student' . $applicant['email'], $hashedPassword, 'student');

                            if (isset($createUserResult['success'])) {
                                // Update the request status to approved
                                if ($studentEnrollmentModel->updateRequestStatus($id, 'approved')) {
                                    Session::set('message', 'Enrollment request approved and applicant registered as a student.');
                                } else {
                                    Session::set('error', 'Failed to update enrollment request status.');
                                }
                            } else {
                                Session::set('error', $createUserResult['error'] ?? 'Failed to create new student user.');
                            }
                        } else {
                            // If the student username already exists, change the status to approved
                            if ($studentEnrollmentModel->updateRequestStatus($id, 'approved')) {
                                Session::set('message', 'Enrollment request approved.');
                            } else {
                                Session::set('error', 'Failed to update enrollment request status.');
                            }
                        }
                    } elseif ($currentStatus === 'rejected') {
                        // Show confirmation dialog for approving a rejected request
                        Session::set('confirm_message', 'This request was previously rejected. Are you sure you want to approve it?');
                        $_SESSION['pending_approval'] = $id; // Store the ID for later confirmation
                    }
                } else {
                    Session::set('error', 'Applicant not found.');
                }
            } else {
                Session::set('error', 'Enrollment request already approved.');
            }
        }

        header('Location: ' . BASE_URL . '?url=admin/view_enrollment_requests');
        exit();
    }





    #[NoReturn] public function rejectEnrollmentRequest(): void
    {
        Session::init();
        $this->authorize('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['request_id'];
            $studentEnrollmentModel = $this->model('StudentEnrollment');

            // Get the current status of the enrollment request
            $currentStatus = $studentEnrollmentModel->getRequestStatus($id);

            if ($currentStatus === 'pending') {
                // Only allow rejection if status is pending
                if ($studentEnrollmentModel->updateRequestStatus($id, 'rejected')) {
                    Session::set('message', 'Enrollment request rejected.');
                } else {
                    Session::set('error', 'Failed to reject the enrollment request.');
                }
            } elseif ($currentStatus === 'approved') {
                // Do not allow rejection if the status is already approved
                Session::set('error', 'Enrollment request already approved and cannot be rejected.');
            } else {
                // Handle other statuses if necessary
                Session::set('error', 'Cannot reject this enrollment request.');
            }
        }

        header('Location: ' . BASE_URL . '?url=admin/view_enrollment_requests');
        exit();
    }




    public function professorRequests(): void
    {
        Session::init();
        $this->authorize('admin');

        $requestModel = $this->model('CourseRequest');
        $data['requests'] = $requestModel->getAllRequests(); // Fetch all requests

        $this->view('admin/professor_requests', $data);
    }

    public function updateRequestStatus(): void
    {
        Session::init();
        $this->authorize('admin');

        if (isset($_POST['request_id']) && isset($_POST['status'])) {
            $requestId = $_POST['request_id'];
            $status = $_POST['status'];

            $requestModel = $this->model('CourseRequest');
            $requestModel->updateRequestStatus($requestId, $status);

            // If approved, assign the professor to the course
            if ($status === 'approved') {
                $request = $requestModel->getRequestById($requestId);
                if ($request) {
                    $courseModel = $this->model('Course');
                    $courseModel->assignProfessorToCourse($request['course_id'], $request['professor_id']);
                }
            }
        }

        header('Location: ' . BASE_URL . '?url=admin/professorRequests');
    }

    public function viewCourseEnrollmentRequests() {
        Session::init();
        $this->authorize('admin');

        $requestModel = $this->model('CourseEnrollmentRequest');
        $requests = $requestModel->getAllCourseEnrollmentRequests();

        $data = [
            'requests' => $requests
        ];

        $this->view('admin/course_enrollment_requests', $data);
    }

    public function updateCourseEnrollmentRequestStatus() {
        Session::init();
        $this->authorize('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $requestId = $_POST['request_id'];
            $status = $_POST['status'];

            $requestModel = $this->model('CourseEnrollmentRequest');

            // Fetch the request details
            $request = $requestModel->getRequestById($requestId);
            if ($request && $status === 'approved') {
                // Add student to course if approved
                $requestModel->addStudentToCourse($request['course_id'], $request['student_id']);
            }

            // Update the request status
            $requestModel->updateCourseEnrollmentRequestStatus($requestId, $status);

            header('Location: ' . BASE_URL . '?url=admin/viewCourseEnrollmentRequests');
        }
    }

}
