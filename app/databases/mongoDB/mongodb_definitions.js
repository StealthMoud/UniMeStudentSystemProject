db.createCollection("applicants", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["user_id", "additional_info"],
            properties: {
                user_id: {
                    bsonType: "int",
                    description: "must be an integer and is required"
                },
                additional_info: {
                    bsonType: "object",
                    required: ["name", "email", "address", "previous_education", "grades", "education_level", "major"],
                    properties: {
                        name: {
                            bsonType: "string",
                            description: "must be a string and is required"
                        },
                        email: {
                            bsonType: "string",
                            description: "must be a string and is required"
                        },
                        address: {
                            bsonType: "string",
                            description: "must be a string and is required"
                        },
                        "Previous Education": {
                            bsonType: "string",
                            description: "must be a string and is required"
                        },
                        grade: {
                            bsonType: "string",
                            description: "must be a string and is required"
                        },
                        "educational level": {
                            bsonType: "string",
                            description: "must be a string and is required"
                        },
                        major: {
                            bsonType: "string",
                            description: "must be a string and is required"
                        }
                    }
                }
            }
        }
    }
});

// Create enrollments collection
db.createCollection("enrollments", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["applicant_id", "application_status", "submitted_at"],
            properties: {
                id: {
                    bsonType: "objectId",
                    description: "must be an objectId and is required"
                },
                applicant_id: {
                    bsonType: "int",
                    description: "must be an integer and is required"
                },
                application_status: {
                    enum: ["not_enrolled", "pending", "approved", "rejected"],
                    description: "can only be one of the enum values and defaults to 'not_enrolled'"
                },
                submitted_at: {
                    bsonType: "date",
                    description: "must be a date and is required"
                },
                reviewed_at: {
                    bsonType: ["date", "null"],
                    description: "can be a date or null"
                }
            }
        }
    }
});

// Create applicant_documents collection
db.createCollection("applicant_documents", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["applicant_id", "name", "path", "uploaded_at"],
            properties: {
                applicant_id: {
                    bsonType: "int",
                    description: "must be an integer and is required"
                },
                name: {
                    bsonType: "string",
                    description: "must be a string and is required"
                },
                path: {
                    bsonType: "string",
                    description: "must be a string and is required"
                },
                uploaded_at: {
                    bsonType: "date",
                    description: "must be a date and is required"
                }
            }
        }
    }
});
