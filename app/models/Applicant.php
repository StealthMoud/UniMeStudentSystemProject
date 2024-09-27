<?php

namespace App\models;

use App\core\Model;
use MongoDB\Database as MongoDBDatabase;
use MongoDB\BSON\UTCDateTime;


class Applicant extends Model {


//    public function getApplicationStatus($applicantId) {
//        $query = "SELECT application_status FROM enrollments WHERE applicant_id = ?";
//        $stmt = $this->db->prepare($query);
//        $stmt->bind_param("i", $applicantId);
//        $stmt->execute();
//        $result = $stmt->get_result();
//        $status = $result->fetch_assoc();
//        return $status ? $status['application_status'] : 'not_enrolled';
//    }

    public function getApplicationStatus($applicantId) {
        $collection = $this->mongo_db->selectCollection('enrollments');
        $result = $collection->findOne(['applicant_id' => $applicantId], ['projection' => ['application_status' => 1]]);

        return $result ? $result['application_status'] : 'not_enrolled';
    }

//    public function enroll($userId, $data, $documents, $documentNames, $documentDescriptions): bool
//    {
//        $additionalInfo = json_encode($data);
//
//        // Insert or update applicant's info
//        $query = "INSERT INTO applicants (user_id, additional_info) VALUES (?, ?)
//                  ON DUPLICATE KEY UPDATE additional_info = VALUES(additional_info)";
//        $stmt = $this->db->prepare($query);
//        $stmt->bind_param("is", $userId, $additionalInfo);
//
//        if ($stmt->execute()) {
//
//            // Insert into enrollments table
//            $enrollmentQuery = "INSERT INTO enrollments (applicant_id, application_status, submitted_at) VALUES (?, 'pending', NOW())
//                                ON DUPLICATE KEY UPDATE application_status = 'pending', submitted_at = VALUES(submitted_at)";
//            $enrollmentStmt = $this->db->prepare($enrollmentQuery);
//            $enrollmentStmt->bind_param("i", $userId);
//
//            if ($enrollmentStmt->execute()) {
//                // Debug output for enrollment data
//                echo '<pre>Enrollment Data Inserted: ';
//                echo 'User ID: ' . $userId;
//                echo '</pre>';
//            } else {
//                // Debug output for enrollment data insert failure
//                echo '<pre>Enrollment Data Insert Failed: ';
//                print_r($enrollmentStmt->error);
//                echo '</pre>';
//            }
//
//            // Handle document uploads if documents are provided
//            if (!empty($documents['name'][0])) {
//                $uploadDir = 'uploads/applicant_documents/';
//                if (!is_dir($uploadDir)) {
//                    mkdir($uploadDir, 0777, true);
//                }
//
//                foreach ($documents['name'] as $key => $fileName) {
//                    if (!empty($fileName)) {
//                        // Check for duplicate document name
//                        $docCheckQuery = "SELECT id FROM applicant_documents WHERE applicant_id = ? AND name = ?";
//                        $docCheckStmt = $this->db->prepare($docCheckQuery);
//                        $docCheckStmt->bind_param("is", $userId, $documentNames[$key]);
//                        $docCheckStmt->execute();
//                        $docCheckResult = $docCheckStmt->get_result();
//                        if ($docCheckResult->num_rows > 0) {
//                            // Debug output for duplicate document name
//                            echo '<pre>Duplicate Document Name: ' . htmlspecialchars($documentNames[$key]) . '</pre>';
//                            continue; // Skip this document
//                        }
//
//                        $filePath = $uploadDir . basename($fileName);
//                        if (move_uploaded_file($documents['tmp_name'][$key], $filePath)) {
//                            $docQuery = "INSERT INTO applicant_documents (applicant_id, name, path, description) VALUES (?, ?, ?, ?)";
//                            $docStmt = $this->db->prepare($docQuery);
//                            $docName = $documentNames[$key] ?: $fileName;
//                            $docDescription = $documentDescriptions[$key] ?: '';
//                            $docStmt->bind_param("isss", $userId, $docName, $filePath, $docDescription);
//                            $docResult = $docStmt->execute();
//
//                            // Debug output for document upload
//                            echo '<pre>Document Uploaded: ';
//                            echo "Name: $docName, Path: $filePath, Description: $docDescription, Inserted: " . ($docResult ? 'Yes' : 'No');
//                            echo '</pre>';
//                        }
//                    }
//                }
//            }
//            return true;
//        } else {
//            // Debug output for applicant data insert failure
//            echo '<pre>Applicant Data Insert Failed: ';
//            print_r($stmt->error);
//            echo '</pre>';
//        }
//        return false;
//    }

    public function enroll($userId, $data, $documents, $documentNames, $documentDescriptions): bool
    {
        try {
            // MongoDB collections
            $applicantsCollection = $this->mongo_db->selectCollection('applicants');
            $enrollmentsCollection = $this->mongo_db->selectCollection('enrollments');
            $applicantDocumentsCollection = $this->mongo_db->selectCollection('applicant_documents');

            // Assume $majorModel is an instance of the Major model class
            $majorModel = new Major();

// Fetch applicant data
            $applicant = $applicantsCollection->findOne(['user_id' => $userId]);
            $additionalInfo = [
                'name' => '',
                'email' => '',
                'address' => '',
                'previous_education' => '',
                'grades' => '',
                'education_level' => '',
                'major' => ''
            ];

            if ($applicant && isset($applicant['additional_info'])) {
                $additionalInfo = $applicant['additional_info'];
            }

            // Fetch the major name using the major ID if provided
            if (isset($data['major'])) {
                $majorId = (int)$data['major'];
                $major = $majorModel->getMajorById($majorId);
                if ($major && isset($major['name'])) {
                    $data['major'] = $major['name']; // Replace the major ID with the major name
                }
            }

            // Update fields with the provided data
            foreach ($data as $key => $value) {
                if (isset($additionalInfo[$key]) && $additionalInfo[$key] === '') {
                    $additionalInfo[$key] = $value;
                } elseif (!isset($additionalInfo[$key])) {
                    $additionalInfo[$key] = $value;
                }
            }

            // Upsert applicant's info in MongoDB
            $applicantsCollection->updateOne(
                ['user_id' => $userId],
                ['$set' => ['additional_info' => $additionalInfo]],
                ['upsert' => true]
            );


            // Insert into enrollments collection in MongoDB
            $enrollmentDocument = [
                'applicant_id' => $userId,
                'application_status' => 'pending',
                'submitted_at' => new UTCDateTime()
            ];

            $enrollmentsCollection->insertOne($enrollmentDocument);

            // Handle document uploads if documents are provided
            if (!empty($documents['name'][0])) {
                $uploadDir = 'uploads/applicant_documents/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($documents['name'] as $key => $fileName) {
                    if (!empty($fileName)) {
                        // Check for duplicate document name (if necessary)
                        $existingDocument = $applicantDocumentsCollection->findOne([
                            'applicant_id' => $userId,
                            'name' => $documentNames[$key]
                        ]);

                        if ($existingDocument) {
                            error_log("Duplicate Document Name: {$documentNames[$key]} for user ID: {$userId}");
                            continue; // Skip this document
                        }

                        $filePath = $uploadDir . basename($fileName);
                        if (move_uploaded_file($documents['tmp_name'][$key], $filePath)) {
                            // Insert document information into MongoDB
                            $docDocument = [
                                'applicant_id' => $userId,
                                'name' => $documentNames[$key] ?: $fileName,
                                'path' => $filePath,
                                'uploaded_at' => new UTCDateTime()
                            ];

                            $applicantDocumentsCollection->insertOne($docDocument);
                        } else {
                            error_log("Failed to move uploaded file for user ID: {$userId}, file: {$fileName}");
                        }
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            // Handle exceptions (MongoDB exceptions)
            error_log('Enrollment error for user ID ' . $userId . ': ' . $e->getMessage());
            return false;
        }
    }


//    public function getApplicantData($userId) {
//        $query = "SELECT additional_info FROM applicants WHERE user_id = ?";
//        $stmt = $this->db->prepare($query);
//        $stmt->bind_param("i", $userId);
//        $stmt->execute();
//        $result = $stmt->get_result();
//        $data = $result->fetch_assoc();
//
//        if ($data && !is_null($data['additional_info'])) {
//            $additionalInfo = json_decode($data['additional_info'], true);
//            return is_array($additionalInfo) ? $additionalInfo : [];
//        }
//        return [];
//    }

    public function getApplicantData($userId) {
        $collection = $this->mongo_db->selectCollection('applicants');

        // Find document in MongoDB based on user_id
        $applicant = $collection->findOne(['user_id' => (int)$userId]);

        if ($applicant && isset($applicant['additional_info'])) {
            return $applicant['additional_info'];
        }

        return [];
    }



//    public function getApplicantDocuments($userId) {
//        $query = "SELECT name, path, description FROM applicant_documents WHERE applicant_id = ?";
//        $stmt = $this->db->prepare($query);
//        $stmt->bind_param("i", $userId);
//        $stmt->execute();
//        $result = $stmt->get_result();
//        return $result->fetch_all(MYSQLI_ASSOC);
//    }

    public function getApplicantDocuments($userId): array
    {
        $collection = $this->mongo_db->selectCollection('applicant_documents');

        // Find documents in MongoDB based on applicant_id
        $cursor = $collection->find(['applicant_id' => (int)$userId]);

        $documents = [];
        foreach ($cursor as $doc) {
            $documents[] = [
                'name' => $doc['name'],
                'path' => $doc['path'],
            ];
        }

        return $documents;
    }


//    public function deleteApplicantInfo($userId, $infoType) {
//        $infoFields = ['name', 'email', 'address', 'previous_education', 'grades'];
//
//        if (in_array($infoType, $infoFields)) {
//            $query = "UPDATE applicants SET additional_info = JSON_REMOVE(additional_info, ?) WHERE user_id = ?";
//            $jsonPath = '$."' . $infoType . '"'; // JSON path to remove
//            $stmt = $this->db->prepare($query);
//            $stmt->bind_param("si", $jsonPath, $userId);
//
//            if ($stmt->execute()) {
//                return true;
//            }
//        }
//        return false;
//    }

    public function deleteApplicantInfo($userId, $infoType): bool
    {
        $infoFields = ['name', 'email', 'address', 'previous_education', 'grades'];

        if (in_array($infoType, $infoFields)) {
            $collection = $this->mongo_db->selectCollection('applicants');

            $updateResult = $collection->updateOne(
                ['user_id' => $userId],
                ['$unset' => [$infoType => '']]
            );

            if ($updateResult->getModifiedCount() > 0) {
                return true;
            }
        }
        return false;
    }


//    public function deleteApplicantDocument($userId, $documentName) {
//        // Delete the document entry from the database
//        $query = "DELETE FROM applicant_documents WHERE applicant_id = ? AND name = ?";
//        $stmt = $this->db->prepare($query);
//        $stmt->bind_param("is", $userId, $documentName);
//
//        // Execute query and check if it was successful
//        if ($stmt->execute() && $stmt->affected_rows > 0) {
//            // Delete the file from the filesystem
//            $documentPath = "uploads/applicant_documents/" . $documentName;
//            if (file_exists($documentPath)) {
//                unlink($documentPath);
//            }
//            return true;
//        }
//        return false;
//    }

    public function deleteApplicantDocument($userId, $documentName): bool
    {
        $collection = $this->mongo_db->selectCollection('applicant_documents');

        // Step 1: Delete the document entry from MongoDB
        $deleteResult = $collection->deleteOne([
            'applicant_id' => $userId,
            'name' => $documentName
        ]);

        // Step 2: Check if document was deleted and delete file from filesystem
        if ($deleteResult->getDeletedCount() > 0) {
            $documentPath = "uploads/applicant_documents/" . $documentName;
            if (file_exists($documentPath)) {
                unlink($documentPath);
            }
            return true;
        }
        return false;
    }


//    public function editApplicantInfo($userId, $infoType, $value) {
//        $infoFields = ['name', 'email', 'address', 'previous_education', 'grades'];
//
//        if (in_array($infoType, $infoFields)) {
//            $query = "UPDATE applicants SET additional_info = JSON_SET(additional_info, ?, ?) WHERE user_id = ?";
//            $jsonPath = '$."' . $infoType . '"'; // JSON path to set
//            $stmt = $this->db->prepare($query);
//            $stmt->bind_param("ssi", $jsonPath, $value, $userId);
//
//            if ($stmt->execute()) {
//                return true;
//            }
//        }
//        return false;
//    }

    public function editApplicantInfo($userId, $infoType, $value): bool
    {
        $infoFields = ['name', 'email', 'address', 'previous_education', 'grades'];

        if (in_array($infoType, $infoFields)) {
            $collection = $this->mongo_db->selectCollection('applicants');

            $updateResult = $collection->updateOne(
                ['user_id' => $userId],
                ['$set' => [$infoType => $value]]
            );

            if ($updateResult->getModifiedCount() > 0) {
                return true;
            }
        }
        return false;
    }


}
