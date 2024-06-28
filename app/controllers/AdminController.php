<?php

namespace App\controllers;

use App\Core\Controller;
use App\utilities\Session;
use App\utilities\Validator;

require_once '../app/core/Controller.php';

class AdminController extends Controller {
    public function dashboard() {
        Session::init();
        $this->authorize('admin');
        $this->view('admin/dashboard');
    }

    // Bachelor and Master Majors Management
    public function manageBachelorMajors() {
        $this->manageMajors('bachelor');
    }

    public function manageMasterMajors() {
        $this->manageMajors('master');
    }

    private function manageMajors($level) {
        $majorModel = $this->model('Major');
        if ($majorModel) {
            $majors = $majorModel->getMajorsByLevel($level);
            $this->view('admin/manage_majors', ['majors' => $majors, 'level' => ucfirst($level)]);
        } else {
            echo "Failed to load the Major model.";
        }
    }

    public function addBachelorMajor() {
        $this->addMajor('bachelor');
    }

    public function addMasterMajor() {
        $this->addMajor('master');
    }

    private function addMajor($level) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $majorModel = $this->model('Major');
            $majorName = $_POST['major_name'];
            $majorModel->createMajor($majorName, $level);
        }
        header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_majors');
    }

    public function deleteBachelorMajor() {
        $this->deleteMajor('bachelor');
    }

    public function deleteMasterMajor() {
        $this->deleteMajor('master');
    }

    private function deleteMajor($level) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $majorModel = $this->model('Major');
            $majorId = $_POST['major_id'];
            $majorModel->deleteMajor($majorId);
        }
        header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_majors');
    }

    // Bachelor and Master Courses Management
    public function manageBachelorCourses() {
        $this->manageCourses('bachelor');
    }

    public function manageMasterCourses() {
        $this->manageCourses('master');
    }

    private function manageCourses($level) {
        $courseModel = $this->model('Course');
        $majorModel = $this->model('Major');
        $userModel = $this->model('User');
        $data['courses'] = $courseModel->getCoursesByLevel($level);
        $data['majors'] = $majorModel->getMajorsByLevel($level);
        $data['professors'] = $userModel->getUsersByRole('professor');
        $data['level'] = ucfirst($level);
        $this->view('admin/manage_courses', $data);
    }

    public function addBachelorCourse() {
        $this->addCourse('bachelor');
    }

    public function addMasterCourse() {
        $this->addCourse('master');
    }

    private function addCourse($level) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseModel = $this->model('Course');
            $courseName = $_POST['course_name'];
            $majorId = $_POST['major_id'];
            $professorId = isset($_POST['professor_id']) ? $_POST['professor_id'] : null;
            if ($professorId == '') {
                $professorId = NULL;
            }
            $courseModel->createCourse($courseName, $majorId, $professorId, $level);
        }
        header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_courses');
    }

    public function deleteBachelorCourse() {
        $this->deleteCourse('bachelor');
    }

    public function deleteMasterCourse() {
        $this->deleteCourse('master');
    }

    private function deleteCourse($level) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseModel = $this->model('Course');
            $courseId = $_POST['course_id'];
            $courseModel->deleteCourse($courseId);
        }
        header('Location: ' . BASE_URL . '?url=admin/manage_' . $level . '_courses');
    }

    // User Management
    public function manageUsers() {
        $users = $this->model('User')->getAllUsers();
        $this->view('admin/manage_users', ['users' => $users]);
    }


    public function addUser() {
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


    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'];
            $currentUserId = Session::get('user_id'); // Fetch current user ID from session

            if ($userId == $currentUserId) {
                // Prevent self-deletion for admins
                header('Location: ' . BASE_URL . '?url=admin/manage_users&error=' . urlencode('Admins cannot delete their own accounts.'));
                return;
            }

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

    // Bachelor's and Master's Exam Management
    public function scheduleBachelorExam() {
        $this->scheduleExam('bachelor');
    }

    public function scheduleMasterExam() {
        $this->scheduleExam('master');
    }

    private function scheduleExam($level) {
        $courseModel = $this->model('Course');
        $examModel = $this->model('Exam');

        $courses = $courseModel->getCoursesByLevel($level);
        $exams = $examModel->getExamsByLevel($level);

        $coursesByMajor = [];
        foreach ($courses as $course) {
            $coursesByMajor[$course['major_name']][] = $course;
        }

        $data['courses_by_major'] = $coursesByMajor;
        $data['exams'] = $exams;
        $data['level'] = ucfirst($level);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseId = $_POST['course_id'];
            $date = $_POST['date'];

            $examModel->createExam($courseId, $date, $level);

            header('Location: ' . BASE_URL . '?url=admin/schedule_' . $level . '_exam');
            exit;
        }

        $this->view('admin/schedule_exam', $data);
    }

    public function viewBachelorExams() {
        $this->viewExams('bachelor');
    }

    public function viewMasterExams() {
        $this->viewExams('master');
    }

    private function viewExams($level) {
        $examModel = $this->model('Exam');
        $data['exams'] = $examModel->getExamsByLevel($level);
        $data['level'] = ucfirst($level);
        $this->view('admin/view_scheduled_exams', $data);
    }

    public function deleteBachelorExam() {
        $this->deleteExam('bachelor');
    }

    public function deleteMasterExam() {
        $this->deleteExam('master');
    }

    private function deleteExam($level) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $examId = $_POST['exam_id'];

            $examModel = $this->model('Exam');
            $examModel->deleteExam($examId);

            // Redirect to refresh the list
            header('Location: ' . BASE_URL . '?url=admin/schedule_' . $level . '_exam');
            exit;
        } else {
            // Method not allowed if not POST
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }
    }

    // Enrollment Requests Management
    public function viewEnrollmentRequests() {
        Session::init();
        $this->authorize('admin');

        $studentEnrollmentModel = $this->model('StudentEnrollment');
        $data['enrollmentRequests'] = $studentEnrollmentModel->getAllApplicantRequests();

        $this->view('admin/view_enrollment_requests', $data);
    }


    public function approveEnrollmentRequest() {
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
                        if (!$userModel->isUsernameExists($studentUsername)) {
                            // Create a new student user
                            $newUserId = $userModel->createUser($studentUsername, $applicant['email'], $hashedPassword, 'student');

                            if ($newUserId) {
                                // Update the request status to approved
                                $studentEnrollmentModel->updateRequestStatus($id, 'approved');
                                Session::set('message', 'Enrollment request approved and applicant registered as a student.');
                            } else {
                                Session::set('error', 'Failed to create new student user.');
                            }
                        } else {
                            // If the student username already exists, change the status to approved
                            $studentEnrollmentModel->updateRequestStatus($id, 'approved');
                            Session::set('message', 'Enrollment request approved.');
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



    public function rejectEnrollmentRequest() {
        Session::init();
        $this->authorize('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['request_id'];
            $studentEnrollmentModel = $this->model('StudentEnrollment');

            // Get the current status of the enrollment request
            $currentStatus = $studentEnrollmentModel->getRequestStatus($id);

            if ($currentStatus === 'pending') {
                // Only allow rejection if status is pending
                $studentEnrollmentModel->updateRequestStatus($id, 'rejected');
                Session::set('message', 'Enrollment request rejected.');
            } elseif ($currentStatus === 'approved') {
                // Do not allow rejection if the status is already approved
                Session::set('error', 'Enrollment request already approved and cannot be rejected.');
            } else {
                // Handle other statuses if necessary
                Session::set('error', 'Cannot reject this enrollment request.');
            }
        }
        header('Location: ' . BASE_URL . '?url=admin/view_enrollment_requests');
    }

    public function professorRequests() {
        Session::init();
        $this->authorize('admin');

        $requestModel = $this->model('CourseRequest');
        $data['requests'] = $requestModel->getAllRequests(); // Fetch all requests

        $this->view('admin/professor_requests', $data);
    }

    public function updateRequestStatus() {
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
