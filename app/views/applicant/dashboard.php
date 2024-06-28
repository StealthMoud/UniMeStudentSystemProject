<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .dashboard-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 40px;
        background-color: #e0f7fa; /* Light teal background */
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.5s ease-out;
    }

    /* Heading styling */
    .dashboard-container h2 {
        text-align: center;
        color: #00796b; /* Dark teal */
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
        flex: 1 1 calc(33.333% - 20px);
        max-width: calc(33.333% - 20px);
        padding: 20px;
        text-align: center;
        background-color: #009688; /* Primary color for Applicant Dashboard */
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
        background-color: #00796b;
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
        background-color: #d32f2f;
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
        background-color: #b71c1c;
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

    .status-section {
        margin-top: 30px;
        text-align: center;
        padding: 20px;
        background-color: #f1f8e9; /* Light green background */
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .status-section p {
        font-size: 1.2rem;
        color: #33691e; /* Dark green */
    }

    .status-message {
        font-size: 1.1rem;
        color: #00796b;
        margin-top: 10px;
    }

    .guidance-section {
        margin-top: 40px;
        background-color: #e0f7fa;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .guidance-section h3 {
        font-size: 1.5rem;
        color: #00796b;
        margin-bottom: 15px;
    }

    .guidance-section p {
        font-size: 1.2rem;
        color: #555;
    }

    .guidance-content {
        display: none;
        margin-top: 15px;
    }

    .guidance-content p {
        font-size: 1rem;
        color: #555;
    }

    .guidance-section button {
        background-color: #00796b;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 10px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .guidance-section button:hover {
        background-color: #005a4a;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="dashboard-container">
    <h2>Applicant Dashboard</h2>
    <nav class="dashboard-nav">
        <a href="<?php echo BASE_URL; ?>?url=applicant/enrollUniversity">
            <i class="fas fa-university"></i>
            Enroll in University
        </a>
        <a href="<?php echo BASE_URL; ?>?url=applicant/deleteAccountConfirmation">
            <i class="fas fa-user-slash"></i>
            Delete Account
        </a>
    </nav>
    <div class="status-section">
        <?php $applicationStatus = htmlspecialchars(ucfirst($data['applicationStatus'] ?? 'Not_enrolled')); ?>
        <p>Your current application status is: <strong><?php echo $applicationStatus; ?></strong></p>
        <div class="status-message">
            <?php if ($applicationStatus === 'Not_enrolled'): ?>
                <p>Please enroll and upload your documents and wait until we review them.</p>

            <?php elseif ($applicationStatus === 'Pending'): ?>
                <p>Your application is currently under review. Please check back later for updates.</p>
            <?php elseif ($applicationStatus === 'Approved'): ?>
                <p>Congratulations! Your application has been approved. Your username remains the same, but replace <strong>@applicant.unime.it</strong> with <strong>@student.unime.it </strong>to enter to your new dashboard.</p>
                <p>Password remains unchanged.</p>
            <?php elseif ($applicationStatus === 'Rejected'): ?>
                <p>We regret to inform you that your application has been rejected. Please contact support for further assistance.</p>
            <?php else: ?>
                <p>Status: <?php echo $applicationStatus; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="guidance-section">
        <h3>Application Guidance</h3>
        <button onclick="toggleGuidance()">Show/Hide Guidance</button>
        <div class="guidance-content" id="guidanceContent">
            <p><strong>Enroll in University:</strong> Complete your personal details, educational background, and submit required documents to enroll in the university.</p>
            <p><strong>Delete Account:</strong> You can delete your account if you no longer wish to proceed with your application.</p>
            <p><strong>Application Status:</strong> Check your current application status. You can revisit and modify your application details until your application is under review.</p>
        </div>
    </div>
    <div class="dashboard-logout">
        <a class="logout" href="<?php echo BASE_URL; ?>?url=auth/logout">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</div>

<script>
    function toggleGuidance() {
        var content = document.getElementById('guidanceContent');
        content.style.display = (content.style.display === "none" || content.style.display === "") ? "block" : "none";
    }
</script>

<?php include '../app/views/templates/footer.php'; ?>
