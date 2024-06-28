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
    .form-container input[type="file"],
    .form-container input[type="text"],
    .form-container textarea,
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
    .form-container input[type="file"] {
        cursor: pointer;
        background-color: #ffffff;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-container input[type="file"]::file-selector-button {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .form-container input[type="file"]::file-selector-button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
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
    .materials-list form {
        display: inline;
        margin-left: 10px;
    }
    .materials-list button {
        padding: 8px 15px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }
    .materials-list button:hover {
        background-color: #c82333;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }
    .edit-button {
        padding: 8px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }
    .edit-button:hover {
        background-color: #0056b3;
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
    .edit-panel {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        width: 90%;
        max-width: 600px;
    }
    .edit-panel.active {
        display: block;
    }
    .edit-panel form label {
        margin-top: 10px;
    }
    .edit-panel form button {
        margin-top: 20px;
    }
    .edit-panel .close-button {
        display: block;
        text-align: right;
    }
    .edit-panel .close-button button {
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        padding: 8px 15px;
    }
    .edit-panel .close-button button:hover {
        background-color: #c82333;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
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
    <h2>Upload Materials</h2>

    <?php if (isset($message)): ?>
        <p id="message" class="<?php echo $messageClass ?? 'message'; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="course_id">Course:</label>
            <select id="course_id" name="course_id" required>
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>">
                            <?php echo htmlspecialchars($course['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No courses available</option>
                <?php endif; ?>
            </select>

            <label for="material">Upload Material:</label>
            <input type="file" id="material" name="material" required>

            <label for="file_name">File Name (Optional):</label>
            <input type="text" id="file_name" name="file_name" placeholder="Enter new file name">

            <label for="description">Description (Optional):</label>
            <textarea id="description" name="description" rows="3" placeholder="Enter a description"></textarea>

            <button type="submit">Upload</button>
        </form>
    </div>

    <div class="collapsible-tab" onclick="toggleMaterialsList()">Show Uploaded Materials</div>
    <div class="collapsible-content" id="materialsList">
        <ul class="materials-list">
            <?php if (isset($materials) && !empty($materials)): ?>
                <?php foreach ($materials as $material): ?>
                    <li>
                        <div class="file-info">
                            <a href="<?php echo BASE_URL . 'uploads/' . htmlspecialchars(basename($material['path'])); ?>" target="_blank">
                                <?php echo htmlspecialchars($material['display_name'] ?: $material['file_name']); ?>
                            </a>
                            <?php if (!empty($material['description'])): ?>
                                <p><?php echo htmlspecialchars($material['description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <form action="<?php echo BASE_URL; ?>?url=professor/deleteMaterial" method="post" style="display:inline;">
                            <input type="hidden" name="material_id" value="<?php echo htmlspecialchars($material['id']); ?>">
                            <button type="submit">Delete</button>
                        </form>
                        <button class="edit-button" onclick="openEditPanel('<?php echo htmlspecialchars($material['id']); ?>', '<?php echo htmlspecialchars($material['file_name']); ?>', '<?php echo htmlspecialchars($material['description']); ?>')">Edit</button>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No materials uploaded yet.</p>
            <?php endif; ?>
        </ul>
    </div>

    <a href="<?php echo BASE_URL; ?>?url=professor/dashboard" class="back-button">Back to Professor Dashboard</a>
</div>

<div id="editPanel" class="edit-panel">
    <div class="close-button">
        <button onclick="closeEditPanel()">Close</button>
    </div>
    <form action="<?php echo BASE_URL; ?>?url=professor/editMaterial" method="post">
        <input type="hidden" id="edit_material_id" name="material_id">
        <label for="edit_file_name">File Name:</label>
        <input type="text" id="edit_file_name" name="file_name">
        <label for="edit_description">Description:</label>
        <textarea id="edit_description" name="description" rows="3"></textarea>
        <button type="submit">Edit</button>
    </form>
</div>

<script>
    function toggleMaterialsList() {
        const materialsList = document.getElementById('materialsList');
        materialsList.classList.toggle('open');
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

    function openEditPanel(materialId, fileName, description) {
        document.getElementById('edit_material_id').value = materialId;
        document.getElementById('edit_file_name').value = fileName;
        document.getElementById('edit_description').value = description;
        document.getElementById('editPanel').classList.add('active');
    }

    function closeEditPanel() {
        document.getElementById('editPanel').classList.remove('active');
    }
</script>

<?php include '../app/views/templates/footer.php'; ?>
