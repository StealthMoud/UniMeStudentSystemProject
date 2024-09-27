<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .dashboard-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 40px;
        background-color: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

    /* Navigation styling */
    .dashboard-nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        animation: fadeIn 0.8s ease-out;
    }

    .dashboard-nav a {
        flex: 1 1 200px; /* Consistent flex-basis */
        max-width: 200px; /* Consistent max-width */
        padding: 20px;
        text-align: center;
        background-color: #17a2b8; /* Primary color for Professor Dashboard */
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
        background-color: #138496; /* Hover color adjustment */
    }

    .dashboard-nav a:hover i {
        transform: scale(1.2);
    }

    .dashboard-nav a:hover::after {
        opacity: 1;
    }

    /* Logout button styling */
    .dashboard-logout {
        margin-top: 20px; /* Space between buttons and logout */
        width: 100%; /* Full width for centering */
        display: flex;
        justify-content: center; /* Center the logout button */
    }

    .dashboard-logout a {
        background-color: #dc3545; /* Red background for logout button */
        padding: 20px;
        text-align: center;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 20px;
        font-weight: 500;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        max-width: 200px; /* Same size as other buttons */
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .dashboard-logout a i {
        display: block;
        font-size: 30px;
        margin-right: 10px; /* Adjust icon spacing */
        transition: transform 0.3s ease;
    }

    .dashboard-logout a:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        background-color: #c82333; /* Darker red on hover */
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
            gap: 10px; /* Reduced gap for smaller screens */
        }

        .dashboard-nav a {
            flex: 1 1 calc(50% - 20px); /* Adjusted for two-column layout */
            max-width: calc(50% - 20px);
        }

        .dashboard-logout a {
            flex: 1 1 100%; /* Full width on smaller screens */
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .dashboard-nav a {
            flex: 1 1 100%; /* Full width on very small screens */
            max-width: 100%;
        }

        .dashboard-logout a {
            flex: 1 1 100%; /* Full width on very small screens */
            max-width: 100%;
        }
    }
</style>

<div class="dashboard-container">
    <h2>Professor Dashboard</h2>

    <!-- Navigation for professor actions -->
    <nav class="dashboard-nav">
        <a href="<?php echo BASE_URL; ?>?url=professor/chooseCourses">
            <i class="fas fa-graduation-cap"></i>
            Choose Courses
        </a>
        <a href="<?php echo BASE_URL; ?>?url=professor/view_courses">
            <i class="fas fa-book"></i>
            View Courses
        </a>
        <a href="<?php echo BASE_URL; ?>?url=professor/view_students">
            <i class="fas fa-users"></i>
            View Students
        </a>
        <a href="<?php echo BASE_URL; ?>?url=professor/schedule_lecture">
            <i class="fas fa-calendar"></i>
            Schedule Lecture
        </a>
        <a href="<?php echo BASE_URL; ?>?url=professor/upload_materials">
            <i class="fas fa-upload"></i>
            Upload Materials
        </a>
        <a href="<?php echo BASE_URL; ?>?url=professor/schedule_exam">
            <i class="fas fa-pencil-alt"></i>
            Schedule Exam
        </a>
        <a href="<?php echo BASE_URL; ?>?url=professor/enter_grades">
            <i class="fas fa-clipboard-list"></i>
            Enter Grades
        </a>
    </nav>

    <div class="dashboard-logout">
        <a class="logout" href="<?php echo BASE_URL; ?>?url=auth/logout">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</div>

<?php include '../app/views/templates/footer.php'; ?>
