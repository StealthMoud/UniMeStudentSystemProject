// In register.php

<?php include '../app/views/templates/header.php'; ?>

<style>
    .register-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 50px 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        animation: fadeIn 1s ease-out;
    }

    .register-container h2 {
        color: #343a40;
        margin-bottom: 20px;
        font-size: 2.5rem;
        font-weight: 700;
        animation: fadeInDown 1.2s ease-out;
    }

    .register-container form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        animation: fadeIn 1.4s ease-out;
    }

    .register-container label {
        font-size: 1rem;
        color: #555;
        text-align: left;
        font-weight: 600;
    }

    .register-container input {
        padding: 10px 15px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .register-container input:focus {
        border-color: #007bff;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .register-container small {
        text-align: left;
        color: #6c757d;
    }

    .register-container button {
        padding: 12px 20px;
        font-size: 1.2rem;
        color: white;
        background-color: #28a745;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .register-container button:hover {
        background-color: #218838;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .error-message {
        color: red;
        font-size: 1rem;
        margin-top: 10px;
        animation: fadeIn 1.2s ease-out;
    }

    .register-container .info-message {
        margin-top: 20px;
        color: #007bff;
        font-size: 0.9rem;
        animation: fadeIn 1.2s ease-out;
    }

    .register-container .back-button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        font-size: 1rem;
        color: white;
        text-decoration: none;
        background-color: #6c757d;
        border-radius: 6px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .register-container .back-button:hover {
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
</style>

<div class="register-container">
    <h2>Register</h2>
    <form action="<?php echo BASE_URL; ?>?url=auth/register" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required placeholder="username@applicant.unime.it">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required placeholder="At least 8 characters, including letters, numbers, and symbols">

        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <button type="submit">Register</button>
    </form>

    <a href="<?php echo BASE_URL; ?>?url=auth/login" class="back-button">Back to Login</a>
</div>

<?php include '../app/views/templates/footer.php'; ?>
