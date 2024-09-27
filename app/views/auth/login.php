<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .login-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 50px 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        animation: fadeIn 1s ease-out;
    }

    /* Heading styling */
    .login-container h2 {
        color: #343a40;
        margin-bottom: 20px;
        font-size: 2.5rem;
        font-weight: 700;
        animation: fadeInDown 1.2s ease-out;
    }

    /* Form styling */
    .login-container form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        animation: fadeIn 1.4s ease-out;
    }

    .login-container label {
        font-size: 1rem;
        color: #555;
        text-align: left;
    }

    .login-container input {
        padding: 10px 15px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .login-container input:focus {
        border-color: #007bff;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .login-container button {
        padding: 12px 20px;
        font-size: 1.2rem;
        color: white;
        background-color: #007bff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-container button:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Error message styling */
    .login-container p.error {
        color: #ff0000;
        font-size: 1rem;
        margin-top: 20px;
        animation: fadeIn 1.6s ease-out;
    }

    /* Link styling */
    .login-container .register-link {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 20px;
        font-size: 1rem;
        color: white;
        text-decoration: none;
        background-color: #28a745;
        border-radius: 6px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .login-container .register-link:hover {
        background-color: #218838;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
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
</style>

<div class="login-container">
    <h2>Login</h2>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>

    <?php if (isset($data['error'])): ?>
        <p class="error"><?php echo $data['error']; ?></p>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>?url=auth/register" class="register-link">Register</a>
</div>

<?php include '../app/views/templates/footer.php'; ?>
