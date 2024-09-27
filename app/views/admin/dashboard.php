<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .dashboard-container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 40px;
        background-color: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.5s ease-out;
    }

    /* Heading styling */
    .dashboard-container h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 600;
        animation: fadeInDown 0.5s ease-out;
    }

    .section-heading {
        text-align: center;
        color: #495057;
        margin-bottom: 20px;
        font-size: 28px;
        font-weight: 500;
        animation: fadeInDown 0.5s ease-out;
    }

    /* Navigation styling */
    .dashboard-nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        animation: fadeIn 0.8s ease-out;
        margin-bottom: 40px;
    }

    .dashboard-nav a {
        flex: 1 1 calc(33.333% - 20px);
        max-width: calc(33.333% - 20px);
        padding: 20px;
        text-align: center;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 20px;
        font-weight: 500;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .dashboard-nav a i {
        display: block;
        font-size: 30px;
        margin-bottom: 10px;
        transition: transform 0.3s ease;
    }

    .dashboard-nav a::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dashboard-nav a:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        background-color: #0056b3;
    }

    .dashboard-nav a:hover i {
        transform: scale(1.2);
    }

    .dashboard-nav a:hover::after {
        opacity: 1;
    }

    /* Logout button styling */
    .dashboard-logout {
        margin-top: 20px;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .dashboard-logout a {
        background-color: #dc3545;
        padding: 20px;
        text-align: center;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 20px;
        font-weight: 500;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        max-width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .dashboard-logout a i {
        display: block;
        font-size: 30px;
        margin-right: 10px;
        transition: transform 0.3s ease;
    }

    .dashboard-logout a:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        background-color: #c82333;
    }

    .dashboard-logout a:hover i {
        transform: scale(1.2);
    }

    .dashboard-logout a::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dashboard-logout a:hover::after {
        opacity: 1;
    }

    /* Keyframe animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Media query for responsiveness */
    @media (max-width: 768px) {
        .dashboard-nav {
            gap: 10px;
        }

        .dashboard-nav a {
            flex: 1 1 calc(50% - 20px);
            max-width: calc(50% - 20px);
        }

        .dashboard-logout a {
            flex: 1 1 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .dashboard-nav a {
            flex: 1 1 100%;
            max-width: 100%;
        }

        .dashboard-logout a {
            flex: 1 1 100%;
            max-width: 100%;
        }
    }

    .section {
        margin-bottom: 40px;
    }

    .section-heading {
        font-size: 26px;
        margin-bottom: 20px;
    }
</style>

<div class="dashboard-container">
    <h2>Admin Dashboard</h2>

    <!-- Bachelor's Section -->
    <div class="section">
        <h3 class="section-heading">Bachelor's Programs</h3>
        <nav class="dashboard-nav">
            <a href="<?php echo BASE_URL; ?>?url=admin/manage_bachelor_majors">
                <i class="fas fa-graduation-cap"></i>
                Manage Bachelor's Majors
            </a>
            <a href="<?php echo BASE_URL; ?>?url=admin/manage_bachelor_courses">
                <i class="fas fa-book"></i>
                Manage Bachelor's Courses
            </a>
            <!--<a href="<//?php echo BASE_URL; ?>?url=admin/schedule_bachelor_exam">
                <i class="fas fa-calendar-alt"></i>
                Schedule Bachelor's Exam
            </a>-->
        </nav>
    </div>

    <!-- Master's Section -->
    <div class="section">
        <h3 class="section-heading">Master's Programs</h3>
        <nav class="dashboard-nav">
            <a href="<?php echo BASE_URL; ?>?url=admin/manage_master_majors">
                <i class="fas fa-graduation-cap"></i>
                Manage Master's Majors
            </a>
            <a href="<?php echo BASE_URL; ?>?url=admin/manage_master_courses">
                <i class="fas fa-book"></i>
                Manage Master's Courses
            </a>
            <!--<a href="<//?php echo BASE_URL; ?>?url=admin/schedule_master_exam">
                <i class="fas fa-calendar-alt"></i>
                Schedule Master's Exam
            </a>-->
        </nav>
    </div>

    <!-- General Admin Controls -->
    <div class="section">
        <h3 class="section-heading">General Administration</h3>
        <nav class="dashboard-nav">
            <a href="<?php echo BASE_URL; ?>?url=admin/manage_users">
                <i class="fas fa-users"></i>
                Manage Users
            </a>
            <a href="<?php echo BASE_URL; ?>?url=admin/view_enrollment_requests">
                <i class="fas fa-user-check"></i>
                Enrollment Requests
            </a>
            <a href="<?php echo BASE_URL; ?>?url=admin/professorRequests">
                <i class="fas fa-user-tie"></i>
                Professor Requests
            </a>
            <a href="<?php echo BASE_URL; ?>?url=admin/course_enrollment_requests">
                <i class="fas fa-user-check"></i>
                course Enrollment Requests
            </a>
        </nav>
    </div>

    <!-- Logout Button -->
    <div class="dashboard-logout">
        <a class="logout" href="<?php echo BASE_URL; ?>?url=auth/logout">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</div>

<?php include '../app/views/templates/footer.php'; ?>
