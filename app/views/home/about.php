<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .about-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 50px 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        animation: fadeIn 1s ease-out;
    }

    /* Heading styling */
    .about-container h2 {
        color: #343a40;
        margin-bottom: 20px;
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        animation: fadeInDown 1.2s ease-out;
    }

    /* Paragraph styling */
    .about-container p {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 20px;
        line-height: 1.6;
        animation: fadeIn 1.4s ease-out;
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

<div class="about-container">
    <h2>About University of Messina</h2>
    <p>Welcome to the University of Messina Student Management System. This system is designed to manage student information, courses, and other academic activities efficiently.</p>
    <p>The system is developed as part of a university assignment by Sayed Mahmoud Mohseni, a second-year Data Analysis student.</p>
</div>

<?php include '../app/views/templates/footer.php'; ?>
