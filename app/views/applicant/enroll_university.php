<?php use App\utilities\Session;

include '../app/views/templates/header.php'; ?>

<style>
    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 40px;
        background-color: #f9fbe7; /* Light yellow-green */
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.5s ease-out;
    }

    .container h2 {
        text-align: center;
        color: #558b2f; /* Dark green */
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 600;
        animation: fadeInDown 0.5s ease-out;
    }

    .container label {
        font-size: 1rem;
        color: #33691e; /* Darker green */
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .container input,
    .container textarea,
    .container button,
    .container select {
        width: 100%;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #c5e1a5; /* Light green border */
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .container input:focus,
    .container textarea:focus,
    .container select:focus {
        border-color: #558b2f; /* Dark green border on focus */
        box-shadow: 0 0 6px rgba(85, 139, 47, 0.3);
        outline: none;
    }

    .container button {
        background-color: #7cb342; /* Green button */
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .container button:hover {
        background-color: #558b2f;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .container .guidance {
        background-color: #e8f5e9; /* Very light green */
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #a5d6a7; /* Light green border */
    }

    .container .guidance p {
        font-size: 0.9rem;
        color: #33691e; /* Dark green text */
    }

    .guidance-content {
        display: none;
        margin-top: 15px;
    }

    .guidance-content p {
        font-size: 1rem;
        color: #555;
    }

    .guidance-section button {
        background-color: #00796b;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 10px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .guidance-section button:hover {
        background-color: #005a4a;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .collapsible-tab {
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        background-color: #009688; /* Teal */
        color: white;
        text-align: center;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .collapsible-tab:hover {
        background-color: #00796b;
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
        max-height: 500px; /* Set a max height for the content */
        overflow-y: auto; /* Make content scrollable */
        padding: 15px;
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
    }

    .back-button:hover {
        background-color: #5a6268;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .document-upload {
        margin-top: 20px;
    }

    .document-upload button {
        background-color: #00796b;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .document-upload button:hover {
        background-color: #005a4a;
    }

    .documents-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .documents-list li {
        margin-bottom: 10px;
    }

    .documents-list input {
        display: inline-block;
        width: auto;
    }

    .documents-list button {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .documents-list button:hover {
        background-color: #c82333;
    }

    .delete-info, .edit-info {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 3px 8px;
        border-radius: 3px;
        cursor: pointer;
        margin-left: 5px;
        font-size: 0.8rem;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .delete-info:hover {
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .edit-info:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-size: 1rem;
        position: relative;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
</style>

<div class="container">
    <h2>Enroll in University</h2>
    <?php if (Session::get('message')): ?>
        <div class="alert alert-success" id="success-message"><?php echo htmlspecialchars(Session::get('message')); ?></div>
    <?php endif; ?>
    <?php if (Session::get('error')): ?>
        <div class="alert alert-danger" id="error-message"><?php echo htmlspecialchars(Session::get('error')); ?></div>
    <?php endif; ?>
    <div class="guidance-section">
        <button onclick="toggleGuidance()">Show/Hide Guidance</button>
        <div class="guidance-content" id="guidanceContent">
            <div class="guidance">
                <p><strong>Name:</strong> Your full legal name as it appears on official documents.</p>
                <p><strong>Email:</strong> A valid email address for communication and application tracking.</p>
                <p><strong>Address:</strong> Your current residential address.</p>
                <p><strong>Previous Education:</strong> Details about your previous educational qualifications.</p>
                <p><strong>Grades:</strong> Provide your academic grades from your previous educational institutions.</p>
                <p><strong>Education Level:</strong> Choose your desired education level (Bachelor, Master, etc.).</p>
                <p><strong>Major:</strong> Select a major related to your chosen education level.</p>
                <p><strong>Documents:</strong> Upload necessary documents such as transcripts, certificates, etc.</p>
            </div>
        </div>
    </div>

    <!-- Collapsible tab for enrolling form -->
    <div class="collapsible-tab" onclick="toggleEnrollForm()">Show/Hide Enrollment Form</div>
    <div class="collapsible-content" id="enrollFormContent">
        <?php if ($data['applicationStatus'] !== 'not_enrolled'): ?>
            <p class="alert alert-info">You have already submitted an enrollment request. You cannot submit another until the current request is processed.</p>
        <?php else: ?>
            <form action="<?php echo BASE_URL; ?>?url=applicant/enrollUniversity" method="POST" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required value="<?php echo htmlspecialchars($data['address'] ?? ''); ?>">

                <label for="previous_education">Previous Education:</label>
                <textarea id="previous_education" name="previous_education" required><?php echo htmlspecialchars($data['previous_education'] ?? ''); ?></textarea>

                <label for="grades">Grades:</label>
                <textarea id="grades" name="grades" required><?php echo htmlspecialchars($data['grades'] ?? ''); ?></textarea>

                <label for="education_level">Education Level:</label>
                <select id="education_level" name="education_level" required onchange="loadMajors(this.value)">
                    <option value="">Select Level</option>
                    <option value="bachelor" <?php echo (isset($data['education_level']) && $data['education_level'] == 'bachelor') ? 'selected' : ''; ?>>Bachelor</option>
                    <option value="master" <?php echo (isset($data['education_level']) && $data['education_level'] == 'master') ? 'selected' : ''; ?>>Master</option>
                    <!-- Add other education levels as needed -->
                </select>

                <label for="major">Major:</label>
                <select id="major" name="major" required>
                    <option value="">Select Major</option>
                    <?php if (isset($data['majors'])): ?>
                        <?php foreach ($data['majors'] as $major): ?>
                            <option value="<?php echo htmlspecialchars($major['id']); ?>" <?php echo (isset($data['major']) && $data['major'] == $major['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($major['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <div id="documentsContainer">
                    <div class="document-upload">
                        <label for="document_name">Document Name:</label>
                        <input type="text" name="document_names[]" required>

                        <label for="document_description">Document Description:</label>
                        <textarea name="document_descriptions[]" required></textarea>

                        <label for="documents">Upload Document:</label>
                        <input type="file" name="documents[]" required>
                    </div>
                </div>
                <button type="button" onclick="addDocumentField()">Add Another Document</button>
                <button type="submit">Enroll</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Collapsible tab for viewing submitted information -->
    <div class="collapsible-tab" onclick="toggleSubmittedInfo()">Show/Hide Submitted Information</div>
    <div class="collapsible-content" id="submittedInfo">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($data['name'] ?? 'N/A'); ?>
        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteInfo" method="POST" style="display:inline;">
            <input type="hidden" name="info_type" value="name">
            <button type="submit" class="delete-info" <?php echo empty($data['name']) ? 'disabled' : ''; ?>>Delete</button>
            <button type="button" class="edit-info" onclick="editInfo('name')">Edit</button>
        </form>
        </p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($data['email'] ?? 'N/A'); ?>
        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteInfo" method="POST" style="display:inline;">
            <input type="hidden" name="info_type" value="email">
            <button type="submit" class="delete-info" <?php echo empty($data['email']) ? 'disabled' : ''; ?>>Delete</button>
            <button type="button" class="edit-info" onclick="editInfo('email')">Edit</button>
        </form>
        </p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($data['address'] ?? 'N/A'); ?>
        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteInfo" method="POST" style="display:inline;">
            <input type="hidden" name="info_type" value="address">
            <button type="submit" class="delete-info" <?php echo empty($data['address']) ? 'disabled' : ''; ?>>Delete</button>
            <button type="button" class="edit-info" onclick="editInfo('address')">Edit</button>
        </form>
        </p>
        <p><strong>Previous Education:</strong> <?php echo htmlspecialchars($data['previous_education'] ?? 'N/A'); ?>
        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteInfo" method="POST" style="display:inline;">
            <input type="hidden" name="info_type" value="previous_education">
            <button type="submit" class="delete-info" <?php echo empty($data['previous_education']) ? 'disabled' : ''; ?>>Delete</button>
            <button type="button" class="edit-info" onclick="editInfo('previous_education')">Edit</button>
        </form>
        </p>
        <p><strong>Grades:</strong> <?php echo htmlspecialchars($data['grades'] ?? 'N/A'); ?>
        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteInfo" method="POST" style="display:inline;">
            <input type="hidden" name="info_type" value="grades">
            <button type="submit" class="delete-info" <?php echo empty($data['grades']) ? 'disabled' : ''; ?>>Delete</button>
            <button type="button" class="edit-info" onclick="editInfo('grades')">Edit</button>
        </form>
        </p>
        <p><strong>Education Level:</strong> <?php echo htmlspecialchars($data['education_level'] ?? 'N/A'); ?>
        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteInfo" method="POST" style="display:inline;">
            <input type="hidden" name="info_type" value="education_level">
            <button type="submit" class="delete-info" <?php echo empty($data['education_level']) ? 'disabled' : ''; ?>>Delete</button>
            <button type="button" class="edit-info" onclick="editInfo('education_level')">Edit</button>
        </form>
        </p>
        <p><strong>Major:</strong> <?php echo htmlspecialchars($data['major'] ?? 'N/A'); ?>
        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteInfo" method="POST" style="display:inline;">
            <input type="hidden" name="info_type" value="major">
            <button type="submit" class="delete-info" <?php echo empty($data['major']) ? 'disabled' : ''; ?>>Delete</button>
            <button type="button" class="edit-info" onclick="editInfo('major')">Edit</button>
        </form>
        </p>

        <?php if (!empty($data['documents'])): ?>
            <ul class="documents-list">
                <?php foreach ($data['documents'] as $document): ?>
                    <li>
                        <a href="<?php echo htmlspecialchars(BASE_URL . '/' . $document['path']); ?>" target="_blank"><?php echo htmlspecialchars($document['name']); ?></a>
                        <br>
                        <small><?php echo htmlspecialchars($document['description']); ?></small>
                        <form action="<?php echo BASE_URL; ?>?url=applicant/deleteDocument" method="POST" style="display:inline;">
                            <input type="hidden" name="document_name" value="<?php echo htmlspecialchars($document['name']); ?>">
                            <button type="submit" class="delete-info">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No documents uploaded.</p>
        <?php endif; ?>
    </div>

    <a href="<?php echo BASE_URL; ?>?url=applicant/dashboard" class="back-button">Back to Applicant Dashboard</a>
</div>

<script>
    function toggleGuidance() {
        var content = document.getElementById('guidanceContent');
        content.style.display = (content.style.display === "none" || content.style.display === "") ? "block" : "none";
    }

    function toggleSubmittedInfo() {
        var content = document.getElementById('submittedInfo');
        content.classList.toggle('open');
    }

    function toggleEnrollForm() {
        var content = document.getElementById('enrollFormContent');
        content.classList.toggle('open');
    }

    function addDocumentField() {
        var container = document.getElementById('documentsContainer');
        var newField = document.createElement('div');
        newField.className = 'document-upload';
        newField.innerHTML = `
            <label for="document_name">Document Name:</label>
            <input type="text" name="document_names[]" required>

            <label for="document_description">Document Description:</label>
            <textarea name="document_descriptions[]" required></textarea>

            <label for="documents">Upload Document:</label>
            <input type="file" name="documents[]" required>
        `;
        container.appendChild(newField);
    }

    function editInfo(infoType) {
        var value = prompt("Enter new value for " + infoType + ":");
        if (value !== null) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', "<?php echo BASE_URL; ?>?url=applicant/editInfo", true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send("info_type=" + infoType + "&value=" + encodeURIComponent(value));
        }
    }

    function loadMajors(level) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', "<?php echo BASE_URL; ?>?url=applicant/getMajorsByLevel&level=" + level, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var majors = JSON.parse(xhr.responseText);
                var majorSelect = document.getElementById('major');
                majorSelect.innerHTML = '<option value="">Select Major</option>';
                majors.forEach(function (major) {
                    var option = document.createElement('option');
                    option.value = major.id;
                    option.textContent = major.name;
                    majorSelect.appendChild(option);
                });
            }
        };
        xhr.send();
    }


    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            var errorMessage = document.getElementById('error-message');
            if (successMessage) {
                successMessage.style.opacity = '0';
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 500);
            }
            if (errorMessage) {
                errorMessage.style.opacity = '0';
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 500);
            }
        }, 5000); // 5 seconds

        // Pre-load majors if education level is already selected
        var educationLevel = document.getElementById('education_level').value;
        if (educationLevel) {
            loadMajors(educationLevel);
        }
    });
</script>

<?php include '../app/views/templates/footer.php'; ?>
