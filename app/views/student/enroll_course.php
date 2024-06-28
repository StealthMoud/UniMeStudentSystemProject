<?php include '../app/views/templates/header.php'; ?>

<style>
    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: center;
        animation: fadeIn 0.8s ease-out;
    }

    h2 {
        font-size: 32px;
        color: #343a40;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .form-container {
        background-color: #f1f1f1;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        text-align: left;
        animation: fadeInUp 0.8s ease-out;
    }

    .form-container label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: #495057;
    }

    .form-container select,
    .form-container button {
        width: 100%;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        font-size: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container select {
        cursor: pointer;
        background-color: #ffffff;
    }

    .form-container button {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .form-container button:hover {
        background-color: #0056b3;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    #message {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        transition: opacity 1s ease-out;
    }

    .collapsible-tab {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        background-color: #007bff;
        color: white;
        text-align: center;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
        position: relative;
    }

    .collapsible-tab:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .collapsible-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        padding: 0 15px;
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .collapsible-content.open {
        max-height: 400px;
        padding: 15px;
    }

    .enrolled-courses-list {
        list-style-type: none;
        padding: 0;
    }

    .enrolled-courses-list li {
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .enrolled-courses-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .enrolled-courses-list .course-info {
        flex-grow: 1;
        margin-right: 10px;
    }

    .enrolled-courses-list a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    .enrolled-courses-list a:hover {
        text-decoration: underline;
    }

    .back-button {
        display: inline-block;
        padding: 12px 25px;
        margin-top: 20px;
        font-size: 1rem;
        color: white;
        text-decoration: none;
        background: linear-gradient(45deg, #6c757d, #495057);
        border-radius: 8px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: pulse 2s infinite;
    }

    .back-button:hover {
        background-color: #5a6268;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="container">
    <h2>Enroll in Course</h2>

    <?php if (isset($message)): ?>
        <p id="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="form-container">
        <form action="<?php echo BASE_URL; ?>?url=student/enroll_course" method="post">
            <label for="course">Course:</label>
            <select id="course" name="course" required>
                <option value="" disabled selected>Select a course</option>
                <?php usort($courses, fn($a, $b) => strcmp($a['name'], $b['name'])); ?>
                <?php foreach ($courses as $course): ?>
                    <?php
                    $isEnrolled = false;
                    foreach ($enrolledCourses as $enrolledCourse) {
                        if ($enrolledCourse['id'] == $course['id']) {
                            $isEnrolled = true;
                            break;
                        }
                    }
                    ?>
                    <?php if (!$isEnrolled): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>" <?php echo $course['professor_name'] ? '' : 'disabled'; ?>>
                            <?php echo htmlspecialchars($course['name'] . ' (' . $course['major_name'] . ')'); ?>
                            <?php echo $course['professor_name'] ? '' : '(No Professor Assigned)'; ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <button type="submit">Enroll</button>
        </form>
    </div>

    <div class="collapsible-tab" onclick="toggleEnrolledCourses()">Show Enrolled Courses</div>
    <div class="collapsible-content" id="enrolledCoursesList">
        <ul class="enrolled-courses-list">
            <?php if (isset($enrolledCourses) && !empty($enrolledCourses)): ?>
                <?php foreach ($enrolledCourses as $course): ?>
                    <li>
                        <div class="course-info">
                            <?php echo htmlspecialchars($course['name'] . ' (' . $course['major_name'] . ')'); ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No courses enrolled yet.</p>
            <?php endif; ?>
        </ul>
    </div>

    <div class="collapsible-tab" onclick="togglePendingRequests()">Show Pending Requests</div>
    <div class="collapsible-content" id="pendingRequestsList">
        <ul class="enrolled-courses-list">
            <?php if (isset($pendingRequests) && !empty($pendingRequests)): ?>
                <?php foreach ($pendingRequests as $course): ?>
                    <li>
                        <div class="course-info">
                            <?php echo htmlspecialchars($course['name'] . ' (' . $course['major_name'] . ')'); ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No pending requests.</p>
            <?php endif; ?>
        </ul>
    </div>

    <a href="<?php echo BASE_URL; ?>?url=student/dashboard" class="back-button">Back to Student Dashboard</a>
</div>

<script>
    function toggleEnrolledCourses() {
        const enrolledCoursesList = document.getElementById('enrolledCoursesList');
        enrolledCoursesList.classList.toggle('open');
    }

    function togglePendingRequests() {
        const pendingRequestsList = document.getElementById('pendingRequestsList');
        pendingRequestsList.classList.toggle('open');
    }

    // Remove the message smoothly after 5 seconds
    setTimeout(function() {
        const messageElement = document.getElementById('message');
        if (messageElement) {
            messageElement.style.opacity = '0';
            setTimeout(function() {
                messageElement.remove();
            }, 1000); // Wait for the transition to finish
        }
    }, 5000);
</script>

<?php include '../app/views/templates/footer.php'; ?>
