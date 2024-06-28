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

    /* List of users styling */
    .users-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .users-list li {
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

    .users-list li:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .users-list .user-info {
        font-weight: bold;
        color: #495057;
        font-size: 18px;
    }

    .users-list .user-role {
        font-size: 14px;
        color: #6c757d;
        margin-top: 5px;
    }

    .users-list form {
        display: inline;
        margin-left: 10px;
    }

    .users-list button {
        padding: 8px 15px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }

    .users-list button:hover {
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

    /* No users found message */
    .no-users {
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

    /* Messages styling */
    .success-message {
        color: #28a745;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        padding: 15px;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        animation: fadeIn 0.8s ease-out;
    }

    .error-message {
        color: #dc3545;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 15px;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        animation: fadeIn 0.8s ease-out;
    }
</style>

<div class="container">
    <h2>Manage Users</h2>

    <!-- Show success or error message -->
    <?php if (!empty($_GET['message'])): ?>
        <div class="success-message" id="message-box"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="error-message" id="message-box"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <!-- Form to add a new user -->
    <div class="form-container">
        <form action="<?php echo BASE_URL; ?>?url=admin/add_user" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="professor">Professor</option>
                <option value="admin">Admin</option>
                <option value="applicant">Applicant</option>
            </select>

            <button type="submit">Add User</button>
        </form>
    </div>

    <!-- Outer collapsible tab for all user roles -->
    <div class="collapsible-tab" onclick="toggleRoleList()">Show All Users</div>
    <div class="collapsible-content" id="roleList">
        <!-- Admins -->
        <div class="collapsible-tab" onclick="toggleRole('admins')">Admins</div>
        <div class="collapsible-content" id="admins">
            <ul class="users-list">
                <?php if (!empty($data['users'])): ?>
                    <?php
                    $admins = array_filter($data['users'], function($user) { return $user['role'] === 'admin'; });
                    usort($admins, function($a, $b) {
                        return strcasecmp($a['username'], $b['username']);
                    });
                    if (!empty($admins)) {
                        foreach ($admins as $user): ?>
                            <li>
                                <div class="user-info">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                    <div class="user-role">
                                        Role: <?php echo htmlspecialchars($user['role']); ?>
                                    </div>
                                </div>
                                <form action="<?php echo BASE_URL; ?>?url=admin/delete_user" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </li>
                        <?php endforeach;
                    } else {
                        echo '<li class="no-users">No admins found.</li>';
                    }
                    ?>
                <?php else: ?>
                    <li class="no-users">No users found.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Professors -->
        <div class="collapsible-tab" onclick="toggleRole('professors')">Professors</div>
        <div class="collapsible-content" id="professors">
            <ul class="users-list">
                <?php if (!empty($data['users'])): ?>
                    <?php
                    $professors = array_filter($data['users'], function($user) { return $user['role'] === 'professor'; });
                    usort($professors, function($a, $b) {
                        return strcasecmp($a['username'], $b['username']);
                    });
                    if (!empty($professors)) {
                        foreach ($professors as $user): ?>
                            <li>
                                <div class="user-info">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                    <div class="user-role">
                                        Role: <?php echo htmlspecialchars($user['role']); ?>
                                    </div>
                                </div>
                                <form action="<?php echo BASE_URL; ?>?url=admin/delete_user" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </li>
                        <?php endforeach;
                    } else {
                        echo '<li class="no-users">No professors found.</li>';
                    }
                    ?>
                <?php else: ?>
                    <li class="no-users">No users found.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Students -->
        <div class="collapsible-tab" onclick="toggleRole('students')">Students</div>
        <div class="collapsible-content" id="students">
            <ul class="users-list">
                <?php if (!empty($data['users'])): ?>
                    <?php
                    $students = array_filter($data['users'], function($user) { return $user['role'] === 'student'; });
                    usort($students, function($a, $b) {
                        return strcasecmp($a['username'], $b['username']);
                    });
                    if (!empty($students)) {
                        foreach ($students as $user): ?>
                            <li>
                                <div class="user-info">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                    <div class="user-role">
                                        Role: <?php echo htmlspecialchars($user['role']); ?>
                                    </div>
                                </div>
                                <form action="<?php echo BASE_URL; ?>?url=admin/delete_user" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </li>
                        <?php endforeach;
                    } else {
                        echo '<li class="no-users">No students found.</li>';
                    }
                    ?>
                <?php else: ?>
                    <li class="no-users">No users found.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Applicants -->
        <div class="collapsible-tab" onclick="toggleRole('applicants')">Applicants</div>
        <div class="collapsible-content" id="applicants">
            <ul class="users-list">
                <?php if (!empty($data['users'])): ?>
                    <?php
                    $applicants = array_filter($data['users'], function($user) { return $user['role'] === 'applicant'; });
                    usort($applicants, function($a, $b) {
                        return strcasecmp($a['username'], $b['username']);
                    });
                    if (!empty($applicants)) {
                        foreach ($applicants as $user): ?>
                            <li>
                                <div class="user-info">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                    <div class="user-role">
                                        Role: <?php echo htmlspecialchars($user['role']); ?>
                                    </div>
                                </div>
                                <form action="<?php echo BASE_URL; ?>?url=admin/delete_user" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </li>
                        <?php endforeach;
                    } else {
                        echo '<li class="no-users">No applicants found.</li>';
                    }
                    ?>
                <?php else: ?>
                    <li class="no-users">No users found.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Back to Admin Dashboard button -->
    <a href="<?php echo BASE_URL; ?>?url=admin/dashboard" class="back-button">Back to Admin Dashboard</a>
</div>

<script>
    function toggleRoleList() {
        const roleList = document.getElementById('roleList');
        roleList.classList.toggle('open');
    }

    function toggleRole(role) {
        const roleContent = document.getElementById(role);
        roleContent.classList.toggle('open');
    }

    // Automatically hide messages after a few seconds
    document.addEventListener('DOMContentLoaded', function() {
        const messageBox = document.getElementById('message-box');
        if (messageBox) {
            setTimeout(() => {
                messageBox.style.display = 'none';
            }, 5000); // 5 seconds
        }
    });
</script>

<?php include '../app/views/templates/footer.php'; ?>
