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
    .lectures-list {
        list-style-type: none;
        padding: 0;
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
    <h2>Scheduled Lectures</h2>

    <ul class="lectures-list">
        <?php if (!empty($data['scheduledLectures'])): ?>
            <?php foreach ($data['scheduledLectures'] as $lecture): ?>
                <li>
                    <div class="lecture-info">
                        <strong><?php echo htmlspecialchars($lecture['course_name']); ?></strong>
                        <b><em>Title:</b> <?php echo htmlspecialchars($lecture['title']); ?><br></em>
                        <b><em>Description:</b> <?php echo htmlspecialchars($lecture['description']); ?><br></em>
                        <b><em>Scheduled at:</b> <?php echo htmlspecialchars($lecture['scheduled_at']); ?><br>
                        <b><em>Major:</b> <?php echo htmlspecialchars($lecture['major_name']); ?></em>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No lectures scheduled yet.</li>
        <?php endif; ?>
    </ul>

    <a href="<?php echo BASE_URL; ?>?url=student/dashboard" class="back-button">Back to Student Dashboard</a>
</div>

<?php include '../app/views/templates/footer.php'; ?>
