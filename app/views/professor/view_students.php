<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.8s ease-out;
    }

    /* Heading styling */
    h2 {
        font-size: 32px;
        color: #343a40;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
    }

    /* Form container styling */
    .form-container {
        text-align: left;
        margin-bottom: 20px;
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
        background-color: #28a745;
        color: white;
        cursor: pointer;
        border: none;
    }

    .form-container button:hover {
        background-color: #218838;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    /* Collapsible tab styling */
    .collapsible-tab {
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        background-color: #007bff;
        color: white;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
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
        border-radius: 8px;
    }

    .collapsible-content.open {
        max-height: 400px;
        padding: 15px;
        overflow-y: auto;
    }

    /* List styling */
    .students-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .students-list li {
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .students-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Back to Dashboard button */
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

    /* Keyframe animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    }
</style>

<div class="container">
    <h2>View Students</h2>

    <div class="form-container">
        <form action="" method="post">
            <label for="course">Select Course:</label>
            <select id="course" name="course_id" required>
                <option value="" disabled selected>Select a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['id']); ?>">
                        <?php echo htmlspecialchars($course['name'] . ' (' . $course['major_name'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">View Students</button>
        </form>
    </div>

    <?php if (!empty($students)): ?>
        <div class="collapsible-tab" onclick="toggleStudentsList()">Show Enrolled Students</div>
        <div class="collapsible-content" id="studentsList">
            <ul class="students-list">
                <?php usort($students, fn($a, $b) => strcmp($a['name'], $b['name'])); ?>
                <?php foreach ($students as $student): ?>
                    <li>
                        <div>
                            <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <p>No students enrolled in this course.</p>
    <?php endif; ?>

    <!-- Back to Professor Dashboard button -->
    <a href="<?php echo BASE_URL; ?>?url=professor/dashboard" class="back-button">Back to Professor Dashboard</a>
</div>

<script>
    function toggleStudentsList() {
        const studentsList = document.getElementById('studentsList');
        studentsList.classList.toggle('open');
    }
</script>

<?php include '../app/views/templates/footer.php'; ?>
