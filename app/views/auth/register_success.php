<?php include '../app/views/templates/header.php'; ?>

<style>
    .success-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 50px 30px;
        background-color: #e6ffed;
        border: 1px solid #b7f1c8;
        border-radius: 12px;
        text-align: center;
        animation: fadeIn 1s ease-out;
    }

    .success-container h2 {
        color: #28a745;
        margin-bottom: 20px;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .success-container p {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }

    .success-container a {
        display: inline-block;
        padding: 12px 25px;
        font-size: 1rem;
        color: white;
        text-decoration: none;
        background-color: #28a745;
        border-radius: 6px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .success-container a:hover {
        background-color: #218838;
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
</style>

<div class="success-container">
    <h2>Registration Successful!</h2>
    <p>Your account has been created successfully. You can now log in using your credentials.</p>
    <a href="<?php echo BASE_URL; ?>?url=auth/login">Go to Login</a>
</div>

<?php include '../app/views/templates/footer.php'; ?>
