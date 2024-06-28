-- Ensure the database exists
CREATE DATABASE IF NOT EXISTS UniMe;
USE UniMe;

-- Create 'users' table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('applicant', 'student', 'professor', 'admin') NOT NULL DEFAULT 'applicant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted BOOLEAN NOT NULL DEFAULT FALSE,
    deleted_at TIMESTAMP NULL -- For soft delete timestamp
    );

-- Create 'majors' table
CREATE TABLE IF NOT EXISTS majors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    level ENUM('bachelor', 'master') NOT NULL, -- Add level for Bachelor's and Master's
    description TEXT DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted BOOLEAN NOT NULL DEFAULT FALSE,
    deleted_at TIMESTAMP NULL -- For soft delete timestamp
    );

-- Create 'applicants' table
CREATE TABLE IF NOT EXISTS applicants (
    user_id INT PRIMARY KEY,
    additional_info JSON NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CHECK (
    JSON_VALID(additional_info) AND
    JSON_TYPE(JSON_EXTRACT(additional_info, '$.name')) = 'STRING' AND
    JSON_TYPE(JSON_EXTRACT(additional_info, '$.email')) = 'STRING' AND
    JSON_TYPE(JSON_EXTRACT(additional_info, '$.address')) = 'STRING' AND
    JSON_TYPE(JSON_EXTRACT(additional_info, '$.Previous Education')) = 'STRING' AND
    JSON_TYPE(JSON_EXTRACT(additional_info, '$.grade')) = 'STRING' AND
    JSON_TYPE(JSON_EXTRACT(additional_info, '$.educational level')) = 'STRING' AND
    JSON_TYPE(JSON_EXTRACT(additional_info, '$.major')) = 'STRING'
    )
    );


-- Create 'students' table
CREATE TABLE IF NOT EXISTS students (
    user_id INT PRIMARY KEY,
    major_id INT,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (major_id) REFERENCES majors(id) ON DELETE SET NULL
    );

-- Create 'professors' table
CREATE TABLE IF NOT EXISTS professors (
    user_id INT PRIMARY KEY,
    additional_info VARCHAR(255) DEFAULT '',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

-- Create 'courses' table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    level ENUM('bachelor', 'master') NOT NULL, -- Add level for Bachelor's and Master's
    description TEXT DEFAULT '',
    credits INT NOT NULL,
    major_id INT,
    professor_id INT,
    schedule TEXT DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted BOOLEAN NOT NULL DEFAULT FALSE,
    deleted_at TIMESTAMP NULL, -- For soft delete timestamp
    FOREIGN KEY (major_id) REFERENCES majors(id) ON DELETE SET NULL,
    FOREIGN KEY (professor_id) REFERENCES professors(user_id) ON DELETE SET NULL
    );

-- Create 'student_enrollments' table
CREATE TABLE IF NOT EXISTS student_enrollments (
    student_id INT,
    course_id INT,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, course_id),
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    );


-- Create 'applicant_documents' table
CREATE TABLE IF NOT EXISTS applicant_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT,
    name VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(user_id) ON DELETE CASCADE
    );

-- Create 'enrollments' table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT,
    application_status ENUM('not_enrolled', 'pending', 'approved', 'rejected') DEFAULT 'not_enrolled',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at DATETIME DEFAULT NULL,
    FOREIGN KEY (applicant_id) REFERENCES applicants(user_id) ON DELETE CASCADE
    );

-- Create 'lectures' table
CREATE TABLE IF NOT EXISTS lectures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    professor_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT '',
    scheduled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professors(user_id) ON DELETE CASCADE
    );

-- Create 'exams' table
CREATE TABLE IF NOT EXISTS exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    professor_id INT,
    exam_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professors(user_id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    professor_id INT NOT NULL,
    grade INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professors(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_grade (student_id, course_id, professor_id)
    );



-- Create 'course_materials' table
CREATE TABLE IF NOT EXISTS course_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT,
    course_id INT,
    file_name VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    description TEXT,
    display_name VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    );


CREATE TABLE course_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    professor_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (professor_id) REFERENCES users(id)
);

CREATE TABLE `course_enrollment_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`),
    FOREIGN KEY (`student_id`) REFERENCES `students`(`user_id`)
);


