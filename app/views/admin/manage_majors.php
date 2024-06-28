<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
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

    /* Heading styling */
    .container h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 600;
        animation: fadeInDown 0.8s ease-out;
    }

    /* Form container styling */
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

    /* Collapsible tab styling */
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

    /* List of majors styling */
    .majors-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .majors-list li {
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

    .majors-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .majors-list .major-name {
        font-weight: bold;
        color: #495057;
        font-size: 18px;
    }

    .majors-list form {
        display: inline;
        margin-left: 10px;
    }

    .majors-list button {
        padding: 8px 15px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .majors-list button:hover {
        background-color: #c82333;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    /* Back to Admin Dashboard button */
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
    }

    .back-button:hover {
        background-color: #5a6268;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* No majors found message */
    .no-majors {
        text-align: center;
        color: #868e96;
        font-style: italic;
        font-size: 18px;
        animation: fadeIn 0.8s ease-out;
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
    <h2>Manage Majors</h2>

    <!-- Form to add a new major -->
    <div class="form-container">
        <form action="<?php echo BASE_URL; ?>?url=admin/add_<?php echo strtolower($level); ?>_major" method="POST">
            <label for="major_name">New <?php echo $level; ?> Major Name:</label>
            <input type="text" id="major_name" name="major_name" required>
            <button type="submit">Add Major</button>
        </form>
    </div>

    <!-- Collapsible tab for majors -->
    <div class="collapsible-tab" onclick="toggleMajorsList()">Show Majors</div>
    <div class="collapsible-content" id="majorsList">
        <ul class="majors-list">
            <?php if (!empty($majors)): ?>
                <?php
                // Sort majors alphabetically by name
                usort($majors, function($a, $b) {
                    return strcasecmp($a['name'], $b['name']);
                });
                foreach ($majors as $major): ?>
                    <li>
                        <span class="major-name"><?php echo htmlspecialchars($major['name']); ?></span>
                        <!-- Form to delete a major -->
                        <form action="<?php echo BASE_URL; ?>?url=admin/delete_<?php echo strtolower($level); ?>_major" method="POST">
                            <input type="hidden" name="major_id" value="<?php echo htmlspecialchars($major['id']); ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="no-majors">No majors found.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Back to Admin Dashboard button -->
    <a href="<?php echo BASE_URL; ?>?url=admin/dashboard" class="back-button">Back to Admin Dashboard</a>
</div>

<script>
    function toggleMajorsList() {
        const majorsList = document.getElementById('majorsList');
        majorsList.classList.toggle('open');
    }
</script>

<?php include '../app/views/templates/footer.php'; ?>
