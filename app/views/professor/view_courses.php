<?php include '../app/views/templates/header.php'; ?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .course-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        animation: fadeIn 1s ease-out;
    }

    .course-container h2 {
        text-align: center;
        color: #4a90e2;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 600;
        animation: fadeInDown 0.8s ease-out;
    }

    .course-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        animation: fadeInUp 0.8s ease-out;
    }

    .course-item {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .course-item:hover {
        background-color: #e0f7fa;
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .course-item h3 {
        margin: 0;
        color: #00796b;
    }

    .course-item p {
        margin: 10px 0;
        color: #555;
    }

    .course-item a {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .course-item a:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .back-button {
        display: inline-block;
        padding: 12px 25px;
        margin: 20px auto;
        font-size: 1rem;
        color: white;
        text-decoration: none;
        background-color: #6c757d;
        border-radius: 6px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: pulse 2s infinite;
        text-align: center;
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

<div class="course-container">
    <h2>Approved Courses</h2>
    <div class="course-list">
        <?php foreach ($courses as $course): ?>
            <div class="course-item">
                <h3><?php echo htmlspecialchars($course['name'] ?? 'N/A'); ?></h3>
                <p><strong>Major:</strong> <?php echo htmlspecialchars($course['major_name'] ?? 'N/A'); ?></p>
                <p><strong>Professor:</strong> <?php echo htmlspecialchars($course['professor_name'] ?? 'N/A'); ?></p>
                <p><strong>Credits:</strong> <?php echo htmlspecialchars((string)$course['credits'] ?? 'N/A'); ?></p>
                <p><strong>Schedule:</strong> <?php echo htmlspecialchars($course['schedule'] ?? 'N/A'); ?></p>
                <p><?php echo htmlspecialchars($course['description'] ?? 'No description available'); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="<?php echo BASE_URL; ?>?url=professor/dashboard" class="back-button">Back to Professor Dashboard</a>
</div>

<?php include '../app/views/templates/footer.php'; ?>
