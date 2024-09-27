<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .contact-container {
        max-width: 800px;
        margin: 60px auto;
        padding: 50px 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        animation: fadeIn 1s ease-out;
    }

    /* Heading styling */
    .contact-container h2 {
        color: #343a40;
        margin-bottom: 30px;
        font-size: 2.5rem;
        font-weight: 700;
        animation: fadeInDown 1.2s ease-out;
    }

    /* Paragraph styling */
    .contact-container p {
        font-size: 1.1rem;
        color: #555;
        margin-bottom: 30px;
        animation: fadeIn 1.4s ease-out;
    }

    /* Buttons container */
    .button-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        animation: fadeInUp 1.5s ease-out;
    }

    /* Button styling */
    .button-container a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 15px 30px;
        font-size: 1.2rem;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .button-container a i {
        margin-right: 10px;
        font-size: 1.5rem;
    }

    .button-container .github {
        background-color: #24292e;
    }

    .button-container .github:hover {
        background-color: #1c1f23;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .button-container .linkedin {
        background-color: #0077b5;
    }

    .button-container .linkedin:hover {
        background-color: #005582;
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

<div class="contact-container">
    <h2>Contact Information</h2>
    <p>If you have any questions or would like to connect, please visit my GitHub and LinkedIn profiles, or email me directly:</p>
    <div class="button-container">
        <a href="https://github.com/StealthMoud" target="_blank" class="github">
            <i class="fab fa-github"></i> GitHub
        </a>
        <a href="https://www.linkedin.com/in/sayed-mahmoud-mohseni-7299b72a1/" target="_blank" class="linkedin">
            <i class="fab fa-linkedin"></i> LinkedIn
        </a>
        <a href="mailto:stealthmoud@gmail.com" class="email" style="background-color: #dd4b39;">
            <i class="fa fa-envelope"></i> Email
        </a>
    </div>
</div>

<?php include '../app/views/templates/footer.php'; ?>
