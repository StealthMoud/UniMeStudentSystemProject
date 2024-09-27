<?php

namespace App\controllers;

use App\core\Controller;
use App\utilities\Session;

class ApplicantController extends Controller {
    public function dashboard(): void
    {
        Session::init();
        $this->authorize('applicant');

        $applicant = $this->model('Applicant');
        $userId = Session::get('user_id');
        $applicationStatus = $applicant->getApplicationStatus($userId);
        $this->view('applicant/dashboard', ['applicationStatus' => $applicationStatus]);
    }

    public function enrollUniversity(): void
    {
        Session::init();

        $applicant = $this->model('Applicant');
        $major = $this->model('Major');
        $userId = Session::get('user_id');
        $applicationStatus = $applicant->getApplicationStatus($userId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($applicationStatus !== 'not_enrolled') {
                Session::set('error', 'You have already submitted an enrollment request.');
                header('Location: ' . BASE_URL . '?url=applicant/enrollUniversity');
                exit;
            }

            // Fetch the major name using the major ID
            $majorName = $major->getMajorById($_POST['major']);
            if (!$majorName) {
                Session::set('error', 'Invalid major selected.');
                header('Location: ' . BASE_URL . '?url=applicant/enrollUniversity');
                exit;
            }

            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'address' => $_POST['address'],
                'previous_education' => $_POST['previous_education'],
                'grades' => $_POST['grades'],
                'education_level' => $_POST['education_level'],
                'major' => $_POST['major'],
            ];

            $documentsUploaded = !empty($_FILES['documents']['name'][0]);

            $success = $applicant->enroll($userId, $data, $documentsUploaded ? $_FILES['documents'] : [], $_POST['document_names'], $_POST['document_descriptions']);

            if ($success) {
                Session::set('message', 'Enrollment in university successful.');
            } else {
                Session::set('error', 'Enrollment failed.');
            }

            header('Location: ' . BASE_URL . '?url=applicant/enrollUniversity');
            exit;
        } else {
            $applicantData = $applicant->getApplicantData($userId);
            $documents = $applicant->getApplicantDocuments($userId);
            $educationalLevel = $applicantData['additional_info']['educational level'] ?? '';
            $majors = $major->getMajorsByLevel($educationalLevel); // Fetch majors based on selected level
            $applicantDataArray = (array) $applicantData;
            $this->view('applicant/enroll_university', array_merge($applicantDataArray, ['documents' => $documents, 'applicationStatus' => $applicationStatus, 'majors' => $majors]));
        }
    }

    public function getMajorsByLevel(): void
    {
        if (isset($_GET['level'])) {
            $level = $_GET['level'];
            $major = $this->model('Major');
            $majors = $major->getMajorsByLevel($level);
            echo json_encode($majors);
        }
    }

    public function deleteInfo(): void
    {
        Session::init();
        $this->authorize('applicant');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $infoType = $_POST['info_type'];
            $applicant = $this->model('Applicant');
            $userId = Session::get('user_id');

            $success = $applicant->deleteApplicantInfo($userId, $infoType);
            if ($success) {
                Session::set('message', ucfirst($infoType) . ' deleted successfully.');
            } else {
                Session::set('error', 'Failed to delete ' . $infoType . '.');
            }

            header('Location: ' . BASE_URL . '?url=applicant/enrollUniversity');
        }
    }

    public function deleteDocument(): void
    {
        Session::init();
        $this->authorize('applicant');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $documentName = $_POST['document_name'];
            $applicant = $this->model('Applicant');
            $userId = Session::get('user_id');

            $success = $applicant->deleteApplicantDocument($userId, $documentName);
            if ($success) {
                Session::set('message', 'Document deleted successfully.');
            } else {
                Session::set('error', 'Failed to delete the document.');
            }

            header('Location: ' . BASE_URL . '?url=applicant/enrollUniversity');
        }
    }

    public function editInfo(): void
    {
        Session::init();
        $this->authorize('applicant');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $infoType = $_POST['info_type'];
            $value = $_POST['value'];
            $applicant = $this->model('Applicant');
            $userId = Session::get('user_id');

            $success = $applicant->editApplicantInfo($userId, $infoType, $value);
            if ($success) {
                Session::set('message', ucfirst($infoType) . ' updated successfully.');
            } else {
                Session::set('error', 'Failed to update ' . $infoType . '.');
            }

            echo json_encode(['success' => $success]);

            header('Location: ' . BASE_URL . '?url=applicant/enrollUniversity');

        }
    }

    public function deleteAccountConfirmation(): void
    {
        Session::init();
        $this->authorize('applicant');
        $this->view('applicant/delete_account');
    }

    public function deleteAccount(): void
    {
        Session::init();
        $userId = Session::get('user_id');
        $user = $this->model('User');
        $user->deleteUser($userId);
        Session::destroy();
        header('Location: ' . BASE_URL . '?url=auth/login');
    }
}
