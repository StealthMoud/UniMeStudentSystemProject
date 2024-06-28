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

    .form-container select,
    .form-container input[type="text"],
    .form-container button {
        width: 100%;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        font-size: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container select {
        cursor: pointer;
        background-color: #ffffff;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container button {
        background-color: #28a745;
        color: white;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .form-container button:hover {
        background-color: #218838;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .student-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .student-list li {
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

    .student-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .student-list .student-info {
        flex-grow: 1;
        margin-right: 10px;
    }

    .student-list input {
        padding: 8px 15px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 16px;
    }

    .student-list .buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .student-list .buttons button {
        padding: 8px 15px;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .student-list .buttons .edit-button {
        background-color: #17a2b8;
        color: white;
        border: none;
        animation: pulse 1.5s infinite;
    }

    .student-list .buttons .edit-button:hover {
        background-color: #138496;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .student-list .buttons .delete-button {
        background-color: #dc3545;
        color: white;
        border: none;
        animation: pulse 1.5s infinite;
    }

    .student-list .buttons .delete-button:hover {
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
        background: linear-gradient(45deg, #6c757d, #495057);
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

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-out;
    }

    .modal-content h2 {
        text-align: center;
        color: #17a2b8;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .modal-content label {
        font-weight: bold;
        color: #495057;
        display: block;
        margin-bottom: 10px;
    }

    .modal-content input[type="text"],
    .modal-content input[type="number"],
    .modal-content button {
        width: 100%;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        font-size: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modal-content button {
        background-color: #17a2b8;
        color: white;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .modal-content button:hover {
        background-color: #138496;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
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
        max-height: 400px; /* Set a max height for the content */
        overflow-y: auto; /* Make content scrollable */
        padding: 15px;
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
    <h2>Enter Grades</h2>

    <?php if (isset($message)): ?>
        <p id="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="" method="post" class="form-container">
        <label for="course_id">Course:</label>
        <select id="course_id" name="course_id" required onchange="this.form.submit()">
            <option value="" disabled selected>Select a course</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo htmlspecialchars($course['id']); ?>" <?php echo (isset($selectedCourseId) && $selectedCourseId == $course['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($course['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (isset($selectedCourseId) && !empty($students)): ?>
        <form action="" method="post" class="form-container">
            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($selectedCourseId); ?>">
            <ul class="student-list">
                <?php foreach ($students as $student): ?>
                    <li>
                        <div class="student-info">
                            <?php echo htmlspecialchars($student['applicant']['name'] ?? 'Name not found'); ?>
                        </div>
                        <input type="number" name="grades[<?php echo htmlspecialchars($student['student_id']); ?>]" min="18" max="30" placeholder="Enter grade" required>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="submit">Submit Grades</button>
        </form>
    <?php endif; ?>

    <div class="collapsible-tab" onclick="toggleGradesList()">Show All Entered Grades</div>
    <div class="collapsible-content" id="gradesList">
        <ul class="grades-list">
            <?php if (!empty($enteredGrades)): ?>
                <?php foreach ($enteredGrades as $grade): ?>
                    <li>
                        <div class="grade-info">
                            <strong>Student:</strong> <?php echo htmlspecialchars($grade['student_name']); ?><br>
                            <strong>Course:</strong> <?php echo htmlspecialchars($grade['course_name']); ?><br>
                            <strong>Grade:</strong> <?php echo htmlspecialchars($grade['grade']); ?>
                        </div>
                        <div class="buttons">
                            <button type="button" class="edit-button" onclick="openEditModal(<?php echo htmlspecialchars($grade['student_id']); ?>, '<?php echo htmlspecialchars($grade['student_name']); ?>', <?php echo htmlspecialchars($grade['grade']); ?>)">Edit</button>
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($selectedCourseId); ?>">
                                <button type="submit" name="delete" value="<?php echo htmlspecialchars($grade['student_id']); ?>" class="delete-button">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No grades entered yet.</p>
            <?php endif; ?>
        </ul>
    </div>

    <a href="<?php echo BASE_URL; ?>?url=professor/dashboard" class="back-button">Back to Professor Dashboard</a>
</div>

<!-- Modal for editing grades -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Grade</h2>
        <form action="" method="post">
            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($selectedCourseId); ?>">
            <input type="hidden" id="edit_student_id" name="edit_student_id">
            <label for="edit_student_name">Student Name:</label>
            <input type="text" id="edit_student_name" name="edit_student_name" readonly>
            <label for="edit_grade">Grade:</label>
            <input type="number" id="edit_grade" name="edit_grade" min="18" max="30" required>
            <button type="submit">Save</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(studentId, studentName, grade) {
        document.getElementById('edit_student_id').value = studentId;
        document.getElementById('edit_student_name').value = studentName;
        document.getElementById('edit_grade').value = grade;
        document.getElementById('editModal').style.display = "block";
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = "none";
    }

    function toggleGradesList() {
        const gradesList = document.getElementById('gradesList');
        gradesList.classList.toggle('open');
    }

    setTimeout(function() {
        const messageElement = document.getElementById('message');
        if (messageElement) {
            messageElement.style.opacity = '0';
            setTimeout(function() {
                messageElement.remove();
            }, 1000);
        }
    }, 5000);
</script>

<?php include '../app/views/templates/footer.php'; ?>
