<?php

namespace App\models;

use App\Core\Model;

class StudentEnrollment extends Model {

    public function getAllApplicantRequests() {
        $query = "SELECT e.id, e.applicant_id, e.application_status, u.username, a.additional_info, m.name as major_name
              FROM enrollments e
              JOIN users u ON e.applicant_id = u.id
              JOIN applicants a ON e.applicant_id = a.user_id
              LEFT JOIN majors m ON JSON_UNQUOTE(JSON_EXTRACT(a.additional_info, '$.major')) = m.id";
        $result = $this->db->query($query);

        if ($result) {
            $requests = $result->fetch_all(MYSQLI_ASSOC);

            // Fetch documents for each applicant
            foreach ($requests as &$request) {
                $request['documents'] = $this->getDocumentsForApplicant($request['applicant_id']);
            }

            return $requests;
        } else {
            return [];
        }
    }



    public function getApplicantByRequestId($id) {
        $query = "SELECT u.id as user_id, u.username, u.password, a.additional_info
              FROM users u
              JOIN enrollments e ON u.id = e.applicant_id
              JOIN applicants a ON e.applicant_id = a.user_id
              WHERE e.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $applicant = $result->fetch_assoc();

        if ($applicant) {
            // Decode the additional info to get the email
            $additionalInfo = json_decode($applicant['additional_info'], true);
            $applicant['email'] = $additionalInfo['email'] ?? null;
        }

        return $applicant;
    }


    public function getRequestStatus($requestId) {
        $query = "SELECT application_status FROM enrollments WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['application_status'] : null;
    }

    public function updateRequestStatus($id, $status) {
        $query = "UPDATE enrollments SET application_status = ?, reviewed_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    private function getDocumentsForApplicant($applicantId) {
        $docQuery = "SELECT * FROM applicant_documents WHERE applicant_id = ?";
        $stmt = $this->db->prepare($docQuery);
        $stmt->bind_param("i", $applicantId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
