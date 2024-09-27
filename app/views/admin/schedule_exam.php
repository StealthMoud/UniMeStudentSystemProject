<?php include '../app/views/templates/header.php'; ?>

<style>
    .container {
        max-width: 900px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.8s ease-out;
    }

    .container h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 600;
        animation: fadeInDown 0.8s ease-out;
    }

    .form-container {
        background-color: #f1f1f1;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
        animation: fadeInUp 0.8s ease-out;
    }

    .form-container label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: #495057;
    }

    .form-container input,
    .form-container select,
    .form-container button {
        width: 100%;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        font-size: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container button {
        background-color: #28a745;
        color: white;
        font-size: 18px;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .form-container button:hover {
        background-color: #218838;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .form-container input[type="date"] {
        font-family: inherit;
        font-size: 16px;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        background-color: #ffffff;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container input[type="date"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }

    .form-container input[type="date"]::placeholder {
        color: #6c757d;
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
        overflow-y: auto;
        padding: 15px;
    }

    .exams-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .exams-list li {
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
        position: relative;
        overflow: hidden;
    }

    .exams-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .exams-list .exam-info {
        font-size: 18px;
        color: #495057;
        font-weight: bold;
    }

    .exams-list .exam-date {
        font-size: 14px;
        color: #6c757d;
        margin-top: 5px;
    }

    .exams-list form {
        display: inline;
        margin-left: 10px;
    }

    .exams-list button {
        padding: 8px 15px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .exams-list button:hover {
        background-color: #c82333;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .back-button {
        display: inline-block;
        padding: 12px 25px;
        margin-top: 20px;
        font-size: 1rem;
        color: white;
        text-decoration: none;
        background-color: #6c757d;
        border-radius: 6px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: pulse 2s infinite;
    }

    .back-button:hover {
        background-color: #5a6268;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .no-exams {
        text-align: center;
        color: #868e96;
        font-style: italic;
        font-size: 18px;
        animation: fadeIn 0.8s ease-out;
    }

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

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container">
    <h2>Schedule Exam</h2>

    <!-- Form to schedule a new exam -->
    <div class="form-container">
        <form action="<?php echo BASE_URL; ?>?url=admin/schedule_<?php echo strtolower($level); ?>_exam" method="POST">
            <label for="major_id">Major:</label>
            <select id="major_id" name="major_id" onchange="filterCourses()" required>
                <option value="">Select Major</option>
                <?php
                // Sort majors alphabetically
                $majors = array_keys($data['courses_by_major']);
                sort($majors);
                foreach ($majors as $major): ?>
                    <option value="<?php echo htmlspecialchars($major); ?>"><?php echo htmlspecialchars($major); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="course_id">Course:</label>
            <select id="course_id" name="course_id" required>
                <option value="">Select Course</option>
                <?php foreach ($data['courses_by_major'] as $major => $courses): ?>
                    <optgroup label="<?php echo htmlspecialchars($major); ?>">
                        <?php
                        // Sort courses alphabetically by name
                        usort($courses, function($a, $b) {
                            return strcasecmp($a['name'], $b['name']);
                        });
                        foreach ($courses as $course): ?>
                            <option data-major="<?php echo htmlspecialchars($major); ?>" value="<?php echo htmlspecialchars($course['id']); ?>">
                                <?php echo htmlspecialchars($course['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <button type="submit">Schedule</button>
        </form>
    </div>

    <!-- Collapsible tab for exams -->
    <div class="collapsible-tab" onclick="toggleExamsList()">Show Scheduled Exams</div>
    <div class="collapsible-content" id="examsList">
        <ul class="exams-list">
            <?php if (!empty($data['exams'])): ?>
                <?php
                // Sort exams alphabetically by course name
                usort($data['exams'], function($a, $b) {
                    return strcasecmp($a['course_name'], $b['course_name']);
                });
                foreach ($data['exams'] as $exam): ?>
                    <li>
                        <div class="exam-info">
                            <strong>Course:</strong> <?php echo htmlspecialchars($exam['course_name']); ?><br>
                            <strong>Major:</strong> <?php echo htmlspecialchars($exam['major_name']); ?>
                            <div class="exam-date">
                                <strong>Date:</strong> <?php echo htmlspecialchars($exam['date']); ?>
                            </div>
                        </div>
                        <!-- Form to delete an exam -->
                        <form action="<?php echo BASE_URL; ?>?url=admin/delete_exam" method="POST">
                            <input type="hidden" name="exam_id" value="<?php echo htmlspecialchars($exam['id']); ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="no-exams">No exams scheduled.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Back to Admin Dashboard button -->
    <a href="<?php echo BASE_URL; ?>?url=admin/dashboard" class="back-button">Back to Admin Dashboard</a>
</div>

<script>
    // Function to filter courses based on selected major
    function filterCourses() {
        var majorSelect = document.getElementById('major_id');
        var selectedMajor = majorSelect.value;
        var courseSelect = document.getElementById('course_id');
        var options = courseSelect.options;

        // Reset the course selection
        courseSelect.value = "";
        for (var i = 0; i < options.length; i++) {
            var option = options[i];
            if (option.getAttribute('data-major') === selectedMajor || option.value === "") {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        }
    }

    function toggleExamsList() {
        const examsList = document.getElementById('examsList');
        examsList.classList.toggle('open');
    }
</script>

<?php include '../app/views/templates/footer.php'; ?>
