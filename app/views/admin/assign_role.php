<!-- In views/admin/assign_role.php -->
<?php include '../app/views/templates/header.php'; ?>

<style>
    .assign-role-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .assign-role-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .assign-role-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .assign-role-container input, .assign-role-container select {
        padding: 10px;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .assign-role-container button {
        padding: 10px;
        font-size: 1rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .assign-role-container button:hover {
        background-color: #0056b3;
    }
</style>

<div class="assign-role-container">
    <h2>Assign Role to <?php echo htmlspecialchars($user['username']); ?></h2>
    <form action="" method="post">
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
            <option value="professor" <?php echo $user['role'] == 'professor' ? 'selected' : ''; ?>>Professor</option>
            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select>
        <button type="submit">Assign Role</button>
    </form>
</div>

<?php include '../app/views/templates/footer.php'; ?>
