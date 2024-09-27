<?php include '../app/views/templates/header.php'; ?>

<style>
    .container {
        max-width: 1000px;
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
    .materials-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .materials-list li {
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
    .materials-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .materials-list .file-info {
        flex-grow: 1;
        margin-right: 10px;
    }
    .materials-list a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }
    .materials-list a:hover {
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
    <h2>Course Materials</h2>

    <ul class="materials-list">
        <?php if (!empty($materials)): ?>
            <?php foreach ($materials as $material): ?>
                <li>
                    <div class="file-info">
                        <strong><?php echo htmlspecialchars($material['course_name']); ?></strong><br>
                        <a href="<?php echo BASE_URL . 'uploads/' . htmlspecialchars(basename($material['path'])); ?>" target="_blank">
                            <?php echo htmlspecialchars($material['display_name'] ?: $material['file_name']); ?>
                        </a>
                        <?php if (!empty($material['description'])): ?>
                            <p><?php echo htmlspecialchars($material['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No materials available.</p>
        <?php endif; ?>
    </ul>

    <a href="<?php echo BASE_URL; ?>?url=student/dashboard" class="back-button">Back to Student Dashboard</a>
</div>

<?php include '../app/views/templates/footer.php'; ?>
