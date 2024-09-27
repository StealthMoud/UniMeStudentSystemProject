<?php

namespace App\models;

use App\core\Model;
use DateTime;
use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class StudentEnrollment extends Model {

//    public function getAllApplicantRequests(): array
//    {
//        $query = "SELECT e.id, e.applicant_id, e.application_status, u.username, a.additional_info, m.name as major_name
//              FROM enrollments e
//              JOIN users u ON e.applicant_id = u.id
//              JOIN applicants a ON e.applicant_id = a.user_id
//              LEFT JOIN majors m ON JSON_UNQUOTE(JSON_EXTRACT(a.additional_info, '$.major')) = m.id";
//        $result = $this->mysql_conn->query($query);
//
//        if ($result) {
//            $requests = $result->fetch_all(MYSQLI_ASSOC);
//
//            // Fetch documents for each applicant
//            foreach ($requests as &$request) {
//                $request['documents'] = $this->getDocumentsForApplicant($request['applicant_id']);
//            }
//
//            return $requests;
//        } else {
//            return [];
//        }
//    }

    public function getAllApplicantRequests(): array
    {
        // Connect to MongoDB collections
        $enrollmentCollection = $this->mongo_db->selectCollection('enrollments');
        $applicantCollection = $this->mongo_db->selectCollection('applicants');

        // Fetch all enrollments
        $enrollmentsCursor = $enrollmentCollection->find();
        $enrollments = iterator_to_array($enrollmentsCursor);

        if (empty($enrollments)) {
            return []; // Return early if there are no enrollments
        }

        // Prepare the applicant IDs to fetch applicant and user data
        $applicantIds = array_map(function($enrollment) {
            return (int)$enrollment['applicant_id']; // Ensure IDs are integers
        }, $enrollments);

        // Fetch all applicants data from MongoDB
        $applicantsCursor = $applicantCollection->find(['user_id' => ['$in' => $applicantIds]]);
        $applicants = iterator_to_array($applicantsCursor);

        if (empty($applicantIds)) {
            return []; // Return early if there are no applicant IDs
        }

        // Fetch related user data from MySQL
        $applicantIdsPlaceholder = implode(',', array_fill(0, count($applicantIds), '?'));
        $userQuery = "SELECT id, username FROM users WHERE id IN ($applicantIdsPlaceholder)";
        $stmt = $this->mysql_conn->prepare($userQuery);

        // Use an array of strings to bind parameters
        $applicantIdsString = array_map('strval', $applicantIds);
        $stmt->bind_param(str_repeat('i', count($applicantIdsString)), ...$applicantIdsString);

        $stmt->execute();
        $userResult = $stmt->get_result();
        $users = $userResult->fetch_all(MYSQLI_ASSOC);

        // Create a mapping of user_id to user data
        $userMap = [];
        foreach ($users as $user) {
            $userMap[$user['id']] = $user['username'];
        }

        // Create a mapping of user_id to applicant data
        $applicantMap = [];
        foreach ($applicants as $applicant) {
            $applicantMap[$applicant['user_id']] = json_decode(json_encode($applicant), true);
        }

        // Combine enrollment, applicant, and user data
        $requests = [];
        foreach ($enrollments as $enrollment) {
            $applicantId = $enrollment['applicant_id'];
            $applicant = $applicantMap[$applicantId];
            $additionalInfo = $applicant['additional_info'];

            // Fetch major name from MySQL
            $majorQuery = "SELECT name FROM majors WHERE id = ?";
            $stmt = $this->mysql_conn->prepare($majorQuery);
            $stmt->bind_param("s", $additionalInfo['major']);
            $stmt->execute();
            $majorResult = $stmt->get_result();
            $major = $majorResult->fetch_assoc();

            $requests[] = [
                'id' => (string)$enrollment['_id'], // convert ObjectId to string
                'applicant_id' => $applicantId,
                'application_status' => $enrollment['application_status'],
                'username' => $userMap[$applicantId] ?? null,
                'additional_info' => $additionalInfo,
                'major_name' => $major['name'] ?? null,
                'documents' => $this->getDocumentsForApplicant($applicantId)
            ];
        }

        return $requests;
    }

    private function getDocumentsForApplicant($applicantId): array
    {
        // Connect to MongoDB
        $documentCollection = $this->mongo_db->selectCollection('applicant_documents');

        // Fetch documents for the given applicant ID from MongoDB
        $documentsCursor = $documentCollection->find(['applicant_id' => (int) $applicantId]);
        $documents = iterator_to_array($documentsCursor);

        return array_map(function($doc) {
            return json_decode(json_encode($doc), true);
        }, $documents);
    }









    public function getApplicantByRequestId($id): array
    {
        // Connect to MongoDB collections
        $enrollmentCollection = $this->mongo_db->selectCollection('enrollments');
        $applicantCollection = $this->mongo_db->selectCollection('applicants');

        // Ensure the ID is an ObjectId
        try {
            $mongoId = new ObjectId($id);
        } catch (Exception $e) {
            error_log("Invalid ObjectId: " . $e->getMessage());
            return [];
        }

        // Log MongoDB ID
        error_log("MongoDB ID: " . $mongoId);

        // Find the enrollment document by the given request ID
        $enrollment = $enrollmentCollection->findOne(['_id' => $mongoId]);
        error_log("Enrollment Document: " . json_encode($enrollment));

        if ($enrollment) {
            $applicantId = $enrollment['applicant_id'];
            error_log("Applicant ID: " . $applicantId);

            // Find the applicant document by the applicant ID
            $applicant = $applicantCollection->findOne(['user_id' => $applicantId]);
            error_log("Applicant Document: " . json_encode($applicant));

            if ($applicant) {
                // Fetch user data from MySQL
                $query = "SELECT id as user_id, username, password FROM users WHERE id = ?";
                $stmt = $this->mysql_conn->prepare($query);
                $stmt->bind_param("i", $applicantId);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                error_log("User Data from MySQL: " . json_encode($user));

                if ($user) {
                    // Convert BSONDocument to JSON string and then decode it to an array
                    $additionalInfoJson = json_encode($applicant['additional_info']);
                    $user['additional_info'] = json_decode($additionalInfoJson, true);
                    $user['email'] = $user['additional_info']['email'] ?? null;

                    return $user;
                }
            }
        }

        return [];
    }



    public function getRequestStatus($requestId)
    {
        // Convert the request ID to MongoDB ObjectId
        $mongoId = new ObjectId($requestId);

        // Connect to MongoDB collection
        $enrollmentCollection = $this->mongo_db->selectCollection('enrollments');

        // Find the enrollment document by the given request ID
        $enrollment = $enrollmentCollection->findOne(['_id' => $mongoId]);

        // Check if the enrollment document is found and return the application status
        return $enrollment ? $enrollment['application_status'] : null;
    }





    public function updateRequestStatus($id, $status): bool
    {
        // Convert the request ID to MongoDB ObjectId
        $mongoId = new ObjectId($id);

        // Connect to MongoDB collection
        $enrollmentCollection = $this->mongo_db->selectCollection('enrollments');

        try {
            // Update the enrollment document in MongoDB
            $updateResult = $enrollmentCollection->updateOne(
                ['_id' => $mongoId],
                ['$set' => ['application_status' => $status, 'submitted_at' => new UTCDateTime()]]
            );

            return $updateResult->getModifiedCount() > 0;
        } catch (Exception $e) {
            // Handle exceptions if necessary (e.g., logging, error handling)
            return false;
        }
    }







}
