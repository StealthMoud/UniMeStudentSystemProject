<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../app/config/constants.php';
require_once '../app/config/database.php';

session_start();

use App\controllers\ApplicantController;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\ProfessorController;
use App\Controllers\StudentController;
use App\Controllers\HomeController;

$router = new Router();

$router->add('', function() {
    $controller = new HomeController();
    $controller->index();
});

$router->add('home/about', function() {
    $controller = new HomeController();
    $controller->about();
});

$router->add('home/contact', function() {
    $controller = new HomeController();
    $controller->contact();
});

$router->add('auth/login', function() {
    $controller = new AuthController();
    $controller->login();
});

$router->add('auth/register', function() {
    $controller = new AuthController();
    $controller->register();
});

$router->add('auth/logout', function() {
    $controller = new AuthController();
    $controller->logout();
});

$router->add('admin/dashboard', function() {
    $controller = new AdminController();
    $controller->dashboard();
});

$router->add('admin/view_enrollment_requests', function() {
    $controller = new AdminController();
    $controller->viewEnrollmentRequests();
});

$router->add('admin/approveEnrollmentRequest', function() {
    $adminController = new AdminController();
    $adminController->approveEnrollmentRequest();
});

$router->add('admin/rejectEnrollmentRequest', function() {
    $adminController = new AdminController();
    $adminController->rejectEnrollmentRequest();
});

$router->add('admin/updateCourseEnrollmentRequestStatus', function() {
    $adminController = new AdminController();
    $adminController->updateCourseEnrollmentRequestStatus();
});

$router->add('admin/viewCourseEnrollmentRequests', function() {
    $adminController = new AdminController();
    $adminController->viewCourseEnrollmentRequests();
});

// Bachelor's routes
$router->add('admin/manage_bachelor_majors', function() {
    $controller = new AdminController();
    $controller->manageBachelorMajors();
});

$router->add('admin/add_bachelor_major', function() {
    $controller = new AdminController();
    $controller->addBachelorMajor();
});

$router->add('admin/delete_bachelor_major', function() {
    $controller = new AdminController();
    $controller->deleteBachelorMajor();
});

$router->add('admin/manage_bachelor_courses', function() {
    $controller = new AdminController();
    $controller->manageBachelorCourses();
});

$router->add('admin/add_bachelor_course', function() {
    $controller = new AdminController();
    $controller->addBachelorCourse();
});

$router->add('admin/delete_bachelor_course', function() {
    $controller = new AdminController();
    $controller->deleteBachelorCourse();
});

$router->add('admin/schedule_bachelor_exam', function() {
    $controller = new AdminController();
    $controller->scheduleBachelorExam();
});

$router->add('admin/view_bachelor_exams', function() {
    $controller = new AdminController();
    $controller->viewBachelorExams();
});

$router->add('admin/delete_bachelor_exam', function() {
    $controller = new AdminController();
    $controller->deleteBachelorExam();
});

// Master's routes
$router->add('admin/manage_master_majors', function() {
    $controller = new AdminController();
    $controller->manageMasterMajors();
});

$router->add('admin/add_master_major', function() {
    $controller = new AdminController();
    $controller->addMasterMajor();
});

$router->add('admin/delete_master_major', function() {
    $controller = new AdminController();
    $controller->deleteMasterMajor();
});

$router->add('admin/manage_master_courses', function() {
    $controller = new AdminController();
    $controller->manageMasterCourses();
});

$router->add('admin/add_master_course', function() {
    $controller = new AdminController();
    $controller->addMasterCourse();
});

$router->add('admin/delete_master_course', function() {
    $controller = new AdminController();
    $controller->deleteMasterCourse();
});

$router->add('admin/schedule_master_exam', function() {
    $controller = new AdminController();
    $controller->scheduleMasterExam();
});

$router->add('admin/view_master_exams', function() {
    $controller = new AdminController();
    $controller->viewMasterExams();
});

$router->add('admin/delete_master_exam', function() {
    $controller = new AdminController();
    $controller->deleteMasterExam();
});

// Other routes remain unchanged
$router->add('admin/manage_users', function() {
    $controller = new AdminController();
    $controller->manageUsers();
});

$router->add('admin/add_user', function() {
    $controller = new AdminController();
    $controller->addUser();
});

$router->add('admin/delete_user', function() {
    $controller = new AdminController();
    $controller->deleteUser();
});

$router->add('admin/professorRequests', function() {
    $controller = new AdminController();
    $controller->professorRequests();
});

$router->add('admin/updateRequestStatus', function() {
    $controller = new AdminController();
    $controller->updateRequestStatus();
});

$router->add('admin/course_enrollment_requests', function() {
    $controller = new AdminController();
    $controller->viewCourseEnrollmentRequests();
});

// Professor routes
$router->add('professor/dashboard', function() {
    $controller = new ProfessorController();
    $controller->dashboard();
});

$router->add('professor/chooseCourses', function() {
    $controller = new ProfessorController();
    $controller->chooseCourses();
});

$router->add('professor/view_courses', function() {
    $controller = new ProfessorController();
    $controller->viewCourses();
});

$router->add('professor/view_students', function() {
    $controller = new ProfessorController();
    $controller->viewStudents();
});

$router->add('professor/schedule_lecture', function() {
    $controller = new ProfessorController();
    $controller->scheduleLecture();
});

$router->add('professor/deleteLecture', function() {
    $controller = new ProfessorController();
    $controller->deleteLecture();
});

$router->add('professor/upload_materials', function() {
    $controller = new ProfessorController();
    $controller->uploadMaterials();
});

$router->add('professor/editMaterial', function() {
    $controller = new ProfessorController();
    $controller->editMaterial();
});

$router->add('professor/deleteMaterial', function() {
    $controller = new ProfessorController();
    $controller->deleteMaterial();
});

$router->add('professor/schedule_exam', function() {
    $controller = new ProfessorController();
    $controller->scheduleExam();
});

$router->add('professor/deleteExam', function() {
    $controller = new ProfessorController();
    $controller->deleteExam();
});

$router->add('professor/enter_grades', function() {
    $controller = new ProfessorController();
    $controller->enterGrades();
});


// Applicant routes
$router->add('applicant/dashboard', function() {
    $controller = new ApplicantController();
    $controller->dashboard();
});

$router->add('applicant/enrollUniversity', function() {
    $controller = new ApplicantController();
    $controller->enrollUniversity();
});

$router->add('applicant/getMajorsByLevel', function() {
    $controller = new ApplicantController();
    $controller->getMajorsByLevel();
});

$router->add('applicant/deleteDocument', function() {
    $controller = new ApplicantController();
    $controller->deleteDocument();
});

$router->add('applicant/deleteInfo', function() {
    $controller = new ApplicantController();
    $controller->deleteInfo();
});

$router->add('applicant/deleteAccountConfirmation', function() {
    $controller = new ApplicantController();
    $controller->deleteAccountConfirmation();
});

$router->add('applicant/deleteAccount', function() {
    $controller = new ApplicantController();
    $controller->deleteAccount();
});

// Student routes
$router->add('student/dashboard', function() {
    $controller = new StudentController();
    $controller->dashboard();
});

$router->add('student/view_details', function() {
    $controller = new StudentController();
    $controller->viewDetails();
});

$router->add('student/enroll_course', function() {
    $controller = new StudentController();
    $controller->enrollCourse();
});

$router->add('student/view_scheduled_lectures', function() {
    $controller = new StudentController();
    $controller->viewScheduledLectures();
});

$router->add('student/view_materials', function() {
    $controller = new StudentController();
    $controller->viewMaterials();
});

$router->add('student/view_scheduled_exams', function() {
    $controller = new StudentController();
    $controller->viewScheduledExams();
});

$router->add('student/view_grades', function() {
    $controller = new StudentController();
    $controller->viewGrades();
});


$url = $_GET['url'] ?? '';
$router->dispatch($url);
