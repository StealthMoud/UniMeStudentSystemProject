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
    .form-container input[type="date"],
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
    .lectures-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .lectures-list li {
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
    .lectures-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .lectures-list .lecture-info {
        flex-grow: 1;
        margin-right: 10px;
    }
    .lectures-list .lecture-info strong {
        display: block;
        margin-bottom: 5px;
    }
    .lectures-list a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }
    .lectures-list a:hover {
        text-decoration: underline;
    }
    .lectures-list form {
        display: inline;
        margin-left: 10px;
    }
    .lectures-list button {
        padding: 8px 15px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }
    .lectures-list button:hover {
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
    <h2>Schedule Lecture</h2>

    <?php if (isset($data['message'])): ?>
        <div id="message">
            <?php echo htmlspecialchars($data['message']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form action="<?php echo BASE_URL; ?>?url=professor/schedule_lecture" method="post">
            <label for="course_id">Course:</label>
            <select id="course_id" name="course_id" required>
                <?php if (!empty($data['courses'])): ?>
                    <?php
                    usort($data['courses'], function($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });
                    foreach ($data['courses'] as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>">
                            <?php echo htmlspecialchars($course['name']) . ' (' . htmlspecialchars($course['major_name']) . ')'; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <button type="submit">Schedule</button>
        </form>
    </div>

    <div class="collapsible-tab" onclick="toggleLecturesList()">Show Scheduled Lectures</div>
    <div class="collapsible-content" id="lecturesList">
        <ul class="lectures-list">
            <?php if (!empty($data['lectures'])): ?>
                <?php foreach ($data['lectures'] as $lecture): ?>
                    <li>
                        <div class="lecture-info">
                            <strong><?php echo htmlspecialchars($lecture['course_name']); ?></strong>
                            <b><em>Title:</b> <?php echo htmlspecialchars($lecture['title']); ?><br></em>
                            <b><em>Description:</b> <?php echo htmlspecialchars($lecture['description']); ?><br></em>
                            <b><em>Scheduled at:</b> <?php echo htmlspecialchars($lecture['scheduled_at']); ?><br>
                            <b><em>Major:</b> <?php echo htmlspecialchars($lecture['major_name']); ?></em>
                        </div>
                        <form action="<?php echo BASE_URL; ?>?url=professor/deleteLecture" method="post">
                            <input type="hidden" name="lecture_id" value="<?php echo htmlspecialchars($lecture['id']); ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No lectures scheduled yet.</li>
            <?php endif; ?>
        </ul>
    </div>

    <a href="<?php echo BASE_URL; ?>?url=professor/dashboard" class="back-button">Back to Professor Dashboard</a>
</div>

<script>
    function toggleLecturesList() {
        const lecturesList = document.getElementById('lecturesList');
        lecturesList.classList.toggle('open');
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
