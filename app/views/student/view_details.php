<?php include '../app/views/templates/header.php'; ?>

<style>
    .details-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.8s ease-out;
    }

    h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 600;
    }

    .details-list {
        list-style: none;
        padding: 0;
    }

    .details-list li {
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .details-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .details-list .detail-label {
        font-weight: bold;
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

<div class="details-container">
    <h2>Student Details</h2>
    <ul class="details-list">
        <?php if (!empty($applicantDetails)): ?>
            <?php foreach ($applicantDetails as $key => $value): ?>
                <?php if ($key != 'educational level' && $key != 'major'): ?>
                    <li>
                        <span class="detail-label"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?>:</span>
                        <span><?php echo htmlspecialchars($value); ?></span>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            <li>
                <span class="detail-label">Major:</span>
                <span><?php echo htmlspecialchars($studentDetails['major'] ?? 'N/A'); ?></span>
            </li>
        <?php else: ?>
            <li>No details available.</li>
        <?php endif; ?>
    </ul>
    <a href="<?php echo BASE_URL; ?>?url=student/dashboard" class="back-button">Back to Student Dashboard</a>
</div>


<?php include '../app/views/templates/footer.php'; ?>
