# UniMeStudentSystem

UniMeStudentSystem is a university project designed as a web application with four main roles: **Admin**, **Student**, **Applicant**, and **Professor**. This system enables applicants to register, admins to manage users and roles, and students and professors to access university functionalities. The system is built using three different databases running inside Docker containers via Docker Compose.

## Table of Contents
- [Project Overview](#project-overview)
- [Roles](#roles)
- [Technologies](#technologies)
- [Setup Instructions](#setup-instructions)
- [Database Configuration](#database-configuration)
  - [MySQL](#mysql)
  - [MongoDB](#mongodb)
  - [Neo4j](#neo4j)
- [Initial Admin Setup](#initial-admin-setup)
- [Accessing the Application](#accessing-the-application)
- [Hash Password Generator](#hash-password-generator)

## Project Overview
The **UniMeStudentSystem** is a web-based application that supports multiple roles:
- **Admin**: Manages other users and roles.
- **Student**: Accesses university-related information.
- **Applicant**: Registers for an account and waits for admin approval.
- **Professor**: Interacts with students and university resources.

Admins have the ability to accept applicants, create new roles, and manage users. The first admin account is created manually in the database.

## Roles
- **Admin**: Manages the system, accepts applicants, and creates new users and roles.
- **Student**: A registered student of the university.
- **Applicant**: New users who apply to join the university.
- **Professor**: Faculty members who can access student and course information.

## Technologies
This project uses the following technologies:
- **MySQL**: For relational database management.
- **MongoDB**: For document-based storage.
- **Neo4j**: For managing relationships between university courses.
- **Docker & Docker Compose**: Containers for running MySQL, MongoDB, Neo4j, and PHP.
- **PHP**: For backend logic and web development.

## Setup Instructions

### Prerequisites
- Docker and Docker Compose installed on your machine.
- Clone the repository and navigate to the project folder.

### Step 1: Run Docker Compose
Start all necessary containers (MySQL, PHP, MongoDB, Neo4j) using Docker Compose:
```bash
docker-compose up -d
```

### Step 2: Access the Application
Once the containers are running, open your browser and navigate to:
```
http://localhost:8080/public/
```

This will load the homepage of the UniMeStudentSystem application.

### Step 3: Insert Database Files
Navigate to the `/app/databases` directory, which contains necessary SQL and MongoDB files for setting up the initial database structure and seeding default data.

#### MySQL Setup
- Use the provided SQL files to create tables and insert default university majors and courses.
- You can access the MySQL database via Docker:
  ```bash
  docker exec -it <mysql-container-id> mysql -u root -p
  ```

#### MongoDB Setup
- Insert the necessary collections for MongoDB using the provided `.json` files.
  ```bash
  docker exec -it <mongo-container-id> mongoimport --db UniMe --collection <collection> --file /path/to/jsonfile
  ```

### Step 4: Neo4j Setup
- After setting up MySQL, you need to migrate courses from MySQL to Neo4j.
- To perform this migration, open the following URL:
  ```
  http://localhost:8080/public/migration
  ```

This will transfer course data from MySQL to Neo4j.

## Database Configuration

### MySQL
The MySQL configuration and credentials (including root passwords) are stored in the `docker-compose.yml` file. Make sure you configure MySQL correctly by following the setup above.

### MongoDB
MongoDB is used for non-relational data storage. You’ll need to insert initial data collections manually using the provided files in `/app/databases`.

### Neo4j
Neo4j is utilized to manage relationships between courses and students. The migration process from MySQL to Neo4j is done through the web interface at `/public/migration`.

## Initial Admin Setup

The first admin user must be inserted manually into the MySQL database.

1. Access MySQL using Docker:
   ```bash
   docker exec -it <mysql-container-id> mysql -u root -p
   ```

2. Switch to the `UniMe` database:
   ```sql
   USE UniMe;
   ```

3. Insert the initial admin account with the following command:
   ```sql
   INSERT INTO users (username, email, password, role)
   VALUES ('admin@admin.unime.it', 'admin@unime.it', '$2y$10$qHebACwNeLYd.tFuCgdAm.LcNEtugCYj1eDpZZfK40vDa1OJLXdYu', 'admin');
   ```

4. The first admin's credentials are:
   - **Username**: `admin@admin.unime.it`
   - **Password**: `.A12345678a.`

Once inserted, you can log in to the admin dashboard using these credentials.

## Accessing the Application
To access the application’s dashboard and features:
1. Open a browser and go to:
   ```
   http://localhost:8080/public/
   ```
2. Log in with the admin credentials to manage the system.

## Hash Password Generator

If you need to generate a new password hash, you can use the PHP password hashing tool provided. 

1. Access the hash password generator by visiting:
   ```
   http://localhost:8080/public/hash_password.php
   ```
2. The PHP file for this functionality is located at `/app/public/hash_password.php`.

