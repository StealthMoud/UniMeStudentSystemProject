<?php

namespace App\controllers;

use App\Core\Controller;
use App\Utilities\Session;

class StudentController extends Controller {
    public function dashboard() {
        Session::init();
        $this->authorize('student');
        $this->view('student/dashboard');
    }

    public function viewDetails() {
        Session::init();
        $this->authorize('student');

        $studentModel = $this->model('Student');
        $studentUserId = $_SESSION['user_id'];

        // Fetch the applicant's user ID using the student's user ID
        $applicantUserId = $studentModel->getApplicantUserIdByStudentUserId($studentUserId);

        $studentDetails = $studentModel->getStudentDetails($applicantUserId);
        $applicantDetails = $studentModel->getApplicantDetails($applicantUserId);

        $data = [
            'studentDetails' => $studentDetails,
            'applicantDetails' => $applicantDetails
        ];

        $this->view('student/view_details', $data);
    }

    public function enrollCourse() {
        Session::init();
        $this->authorize('student');

        $courseModel = $this->model('Course');
        $requestModel = $this->model('CourseEnrollmentRequest');
        $studentModel = $this->model('Student');

        $studentUserId = $_SESSION['user_id'];

        // Fetch the applicant's user ID using the student's user ID
        $applicantUserId = $studentModel->getApplicantUserIdByStudentUserId($studentUserId);

        // Fetch student's level and major
        $studentDetails = $studentModel->getStudentDetails($applicantUserId);
        $level = $studentDetails['level'];
        $major = $studentDetails['major'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseId = $_POST['course'];
            // Check if there's already a pending or approved request for this course
            $existingRequest = $requestModel->getRequestByCourseAndStudent($courseId, $studentUserId);

            if (!$existingRequest) {
                $requestModel->createCourseEnrollmentRequest($courseId, $studentUserId);
                $message = "Your enrollment request has been submitted.";
            } else {
                $message = "You have already submitted a request for this course.";
            }
        }

        $courses = $courseModel->getCoursesByLevelAndMajor($level, $major);
        $enrolledCourses = $requestModel->getEnrolledCourses($studentUserId);
        $pendingRequests = $requestModel->getPendingRequestsByStudent($studentUserId);

        $data = [
            'courses' => $courses,
            'enrolledCourses' => $enrolledCourses,
            'pendingRequests' => $pendingRequests,
            'message' => $message ?? null
        ];

        $this->view('student/enroll_course', $data);
    }

    public function viewScheduledLectures() {
        Session::init();
        $this->authorize('student');

        $lectureModel = $this->model('Lecture');
        $studentId = $_SESSION['user_id'];

        // Get scheduled lectures for enrolled courses
        $scheduledLectures = $lectureModel->getScheduledLecturesByStudent($studentId);

        $data = [
            'scheduledLectures' => $scheduledLectures
        ];

        $this->view('student/view_scheduled_lectures', $data);
    }

    public function viewMaterials() {
        Session::init();
        $this->authorize('student');

        $materialModel = $this->model('Material');
        $studentId = $_SESSION['user_id'];

        $materials = $materialModel->getMaterialsByStudent($studentId);

        $this->view('student/view_materials', ['materials' => $materials]);
    }

    public function viewScheduledExams() {
        Session::init();
        $this->authorize('student');

        $studentId = $_SESSION['user_id'];
        $examModel = $this->model('Exam');
        $scheduledExams = $examModel->getExamsByStudent($studentId);

        $this->view('student/view_scheduled_exams', [
            'exams' => $scheduledExams
        ]);
    }



    public function viewGrades() {
        Session::init();
        $this->authorize('student');

        $gradeModel = $this->model('Grade');
        $studentId = $_SESSION['user_id'];
        $grades = $gradeModel->getGradesByStudent($studentId);

        $this->view('student/view_grades', ['grades' => $grades]);
    }


}
