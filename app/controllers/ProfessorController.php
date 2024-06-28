<?php

namespace App\controllers;

use App\Core\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Major;
use App\Models\Lecture;
use App\Models\Grade;
use App\Utilities\Session;

class ProfessorController extends Controller {
    public function dashboard() {
        Session::init();
        $this->authorize('professor');
        $this->view('professor/dashboard');
    }

    public function chooseCourses() {
        Session::init();
        $this->authorize('professor');

        $majorModel = $this->model('Major');
        $courseModel = $this->model('Course');
        $requestModel = $this->model('CourseRequest');

        $data['bachelorMajors'] = $majorModel->getMajorsByLevel('bachelor');
        $data['masterMajors'] = $majorModel->getMajorsByLevel('master');
        $professorId = $_SESSION['user_id']; // Ensure this is the correct session key

        // Check if a major has been selected
        if (isset($_POST['major_id'])) {
            $majorId = $_POST['major_id'];
            $data['courses'] = $courseModel->getCoursesByMajor($majorId);

            // Fetch the courses that have already been requested by the professor
            $existingRequests = $requestModel->getRequestsByProfessor($professorId);

            // Filter out courses that are already requested (pending or approved)
            $existingCourseIds = array_column(array_filter($existingRequests, function($request) {
                return in_array($request['status'], ['pending', 'approved']);
            }), 'course_id');

            $data['courses'] = array_filter($data['courses'], function($course) use ($existingCourseIds) {
                return !in_array($course['id'], $existingCourseIds);
            });
        } else {
            $data['courses'] = [];
        }

        // Handle course selection form submission
        if (isset($_POST['selected_courses'])) {
            $selectedCourses = $_POST['selected_courses'];

            foreach ($selectedCourses as $courseId) {
                $requestModel->createCourseRequest($courseId, $professorId);
            }

            $data['success'] = "Your course requests have been submitted for approval.";
        }

        // Fetch professor's requests
        $data['requests'] = $requestModel->getRequestsByProfessor($professorId);

        $this->view('professor/choose_courses', $data);
    }

    public function viewCourses() {
        Session::init();
        $this->authorize('professor');

        $courseModel = $this->model('Course');
        $professorId = $_SESSION['user_id']; // Ensure this is the correct session key

        // Fetch only approved courses for the professor
        $courses = $courseModel->getApprovedCoursesByProfessor($professorId);

        $data['courses'] = $courses;
        $this->view('professor/view_courses', $data);
    }

    public function viewStudents() {
        Session::init();
        $this->authorize('professor');

        $courseModel = $this->model('Course');
        $studentModel = $this->model('Student');
        $professorId = $_SESSION['user_id'];

        // Get courses taught by this professor
        $courses = $courseModel->getCoursesByProfessor($professorId);

        $students = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseId = $_POST['course_id'];
            $students = $studentModel->getStudentsByCourse($courseId);
        }

        $data = [
            'courses' => $courses,
            'students' => $students
        ];

        $this->view('professor/view_students', $data);
    }

    public function scheduleLecture() {
        Session::init();
        $courseModel = $this->model('Course');
        $lectureModel = $this->model('Lecture');

        $professorId = $_SESSION['user_id'];

        // Fetch the courses taught by the professor
        $data['courses'] = $courseModel->getCoursesByProfessor($professorId);
        $data['lectures'] = $lectureModel->getLecturesByProfessor($professorId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseId = $_POST['course_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $date = $_POST['date'];

            $lectureModel->scheduleLecture($courseId, $professorId, $title, $description, $date);

            // Set a message to confirm the scheduling
            $data['message'] = 'Lecture scheduled successfully.';

            // Refresh the list of lectures
            $data['lectures'] = $lectureModel->getLecturesByProfessor($professorId);
        }

        $this->view('professor/schedule_lecture', $data);
    }

    public function deleteLecture() {
        Session::init();
        $lectureModel = $this->model('Lecture');
        $courseModel = $this->model('Course');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['lecture_id'])) {
                $lectureId = $_POST['lecture_id'];

                $lectureModel->deleteLecture($lectureId);

                // Set a message to confirm the deletion
                $data['message'] = 'Lecture deleted successfully.';
            } else {
                $data['message'] = 'Lecture ID not provided.';
            }
        }

        $professorId = $_SESSION['user_id'];

        // Refresh the list of lectures
        $data['lectures'] = $lectureModel->getLecturesByProfessor($professorId);
        $data['courses'] = $courseModel->getCoursesByProfessor($professorId);

        $this->view('professor/schedule_lecture', $data);
    }

    public function uploadMaterials() {
        Session::init();
        $this->authorize('professor');

        $professorId = $_SESSION['user_id'];
        $uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/UniMeStudentSys/public/uploads/';
        $materialModel = $this->model('Material');
        $courseModel = $this->model('Course');
        $courses = $courseModel->getCoursesByProfessor($professorId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $materialId = $_POST['material_id'];
                $materialModel->deleteMaterial($materialId);
                $message = "Material successfully deleted.";
            } else {
                $courseId = $_POST['course_id'];
                $fileName = basename($_FILES['material']['name']);
                $displayName = $_POST['file_name'] ?: $fileName;
                $uploadFile = $uploadsDir . $courseId . '/' . $fileName;

                if (!is_dir($uploadsDir . $courseId)) {
                    mkdir($uploadsDir . $courseId, 0777, true);
                }

                if (move_uploaded_file($_FILES['material']['tmp_name'], $uploadFile)) {
                    $materialModel->uploadMaterial($professorId, $courseId, $fileName, $uploadFile, $_POST['description'] ?? '', $displayName);
                    $message = "Material successfully uploaded.";
                } else {
                    $message = "Failed to upload material.";
                }
            }
        }

        $materials = $materialModel->getAllMaterialsByProfessor($professorId);
        $this->view('professor/upload_materials', ['message' => $message ?? null, 'materials' => $materials, 'courses' => $courses]);
    }

    public function deleteMaterial() {
        Session::init();
        $this->authorize('professor');

        $materialModel = $this->model('Material');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $materialId = $_POST['material_id'];
            $materialModel->deleteMaterial($materialId);
            $message = "Material successfully deleted.";
        }

        $professorId = $_SESSION['user_id'];
        $materials = $materialModel->getAllMaterialsByProfessor($professorId);
        $this->view('professor/upload_materials', ['message' => $message ?? null, 'materials' => $materials]);
    }

    public function editMaterial() {
        Session::init();
        $this->authorize('professor');

        $materialModel = $this->model('Material');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $materialId = $_POST['material_id'];
            $fileName = $_POST['file_name'] ?? null;
            $description = $_POST['description'] ?? null;

            $material = $materialModel->getMaterialById($materialId);
            if ($material) {
                // Use existing file_name if new one is not provided
                $fileName = $fileName ?: $material['file_name'];
                // Use existing description if new one is not provided
                $description = $description ?: $material['description'];

                $materialModel->updateMaterial($materialId, $fileName, $material['path'], $description, $fileName);
                $message = "Material successfully updated.";
            } else {
                $message = "Material not found.";
            }
        }

        $professorId = $_SESSION['user_id'];
        $materials = $materialModel->getAllMaterialsByProfessor($professorId);
        $courses = $this->model('Course')->getCoursesByProfessor($professorId); // Add this line to get courses
        $this->view('professor/upload_materials', ['message' => $message ?? null, 'materials' => $materials, 'courses' => $courses]); // Include courses in the view
    }

    public function scheduleExam() {
        Session::init();
        $this->authorize('professor');
        $courseModel = $this->model('Course');
        $examModel = $this->model('Exam');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseId = $_POST['course_id'] ?? null;
            $examDate = $_POST['exam_date'] ?? null;
            $location = $_POST['location'] ?? null;
            $professorId = $_SESSION['user_id'];

            if ($courseId && $examDate && $location) {
                if ($examModel->createExam($courseId, $professorId, $examDate, $location)) {
                    $message = "Exam scheduled successfully.";
                } else {
                    $message = "Failed to schedule exam.";
                }
            } else {
                $message = "All fields are required.";
            }
        }

        $professorId = $_SESSION['user_id'];
        $courses = $courseModel->getCoursesByProfessor($professorId);
        $exams = $examModel->getExamsByProfessor($professorId);

        $this->view('professor/schedule_exam', [
            'message' => $message ?? null,
            'courses' => $courses,
            'exams' => $exams
        ]);
    }

    public function deleteExam() {
        Session::init();
        $this->authorize('professor');
        $examModel = $this->model('Exam');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $examId = $_POST['exam_id'];

            if ($examModel->deleteExam($examId)) {
                $message = "Exam deleted successfully.";
            } else {
                $message = "Failed to delete exam.";
            }
        }

        $professorId = $_SESSION['user_id'];
        $courseModel = $this->model('Course');
        $courses = $courseModel->getCoursesByProfessor($professorId);
        $exams = $examModel->getExamsByProfessor($professorId);

        $this->view('professor/schedule_exam', [
            'message' => $message ?? null,
            'courses' => $courses,
            'exams' => $exams
        ]);
    }

    public function enterGrades() {
        Session::init();
        $this->authorize('professor');

        $courseModel = $this->model('Course');
        $gradeModel = $this->model('Grade');

        $professorId = $_SESSION['user_id'];
        $courses = $courseModel->getCoursesByProfessor($professorId);
        $selectedCourseId = null;
        $students = [];
        $enteredGrades = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseId = $_POST['course_id'];
            $selectedCourseId = $courseId;

            if (isset($_POST['grades'])) {
                $grades = $_POST['grades'];
                foreach ($grades as $studentId => $grade) {
                    if ($grade < 18 || $grade > 30) {
                        $message = "Grade must be between 18 and 30.";
                    } else {
                        $gradeModel->enterGrade($studentId, $courseId, $professorId, $grade);
                        $message = "Grades entered successfully.";
                    }
                }
            }

            if (isset($_POST['edit_student_id'])) {
                $studentId = $_POST['edit_student_id'];
                $grade = $_POST['edit_grade'];
                if ($grade < 18 || $grade > 30) {
                    $message = "Grade must be between 18 and 30.";
                } else {
                    $gradeModel->updateGrade($studentId, $courseId, $professorId, $grade);
                    $message = "Grade updated successfully.";
                }
            }

            if (isset($_POST['delete'])) {
                $studentId = $_POST['delete'];
                $gradeModel->deleteGrade($studentId, $courseId, $professorId);
                $message = "Grade deleted successfully.";
            }

            $students = $gradeModel->getEnrolledStudents($courseId);
            $enteredGrades = $gradeModel->fetchGradesByCourse($courseId);
        }

        $this->view('professor/enter_grades', [
            'message' => $message ?? null,
            'courses' => $courses,
            'students' => $students,
            'selectedCourseId' => $selectedCourseId,
            'enteredGrades' => $enteredGrades
        ]);
    }




}
