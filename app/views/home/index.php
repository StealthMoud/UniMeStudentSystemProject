<?php include '../app/views/templates/header.php'; ?>

<style>
    /* Main container styling */
    .home-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 50px 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        animation: fadeIn 1s ease-out;
        position: relative;
        overflow: hidden;
    }

    /* Heading styling */
    .home-container h2 {
        color: #343a40;
        margin-bottom: 30px;
        font-size: 2.5rem;
        font-weight: 700;
        animation: fadeInDown 1.2s ease-out;
    }

    /* Paragraph styling */
    .home-container p {
        font-size: 1.1rem;
        color: #555;
        margin-bottom: 20px;
        animation: fadeIn 1.4s ease-out;
        line-height: 1.6;
    }

    /* Button container */
    .button-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
        animation: fadeInUp 1.5s ease-out;
    }

    /* Button styling */
    .button-container a {
        display: inline-block;
        padding: 15px 30px;
        margin: 0 10px;
        font-size: 1.2rem;
        color: white;
        text-decoration: none;
        background: linear-gradient(45deg, #007bff, #0056b3);
        border-radius: 6px;
        transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .button-container a:hover {
        background: linear-gradient(45deg, #0056b3, #007bff);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .button-container a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 200%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transition: left 0.3s ease;
    }

    .button-container a:hover::before {
        left: 0;
    }

    /* Decorative background elements */
    .home-container::before, .home-container::after {
        content: '';
        position: absolute;
        background: radial-gradient(circle, rgba(0, 123, 255, 0.1), rgba(0, 123, 255, 0) 70%);
        border-radius: 50%;
        width: 300px;
        height: 300px;
        top: -150px;
        left: -150px;
        z-index: -1;
        animation: rotate 10s infinite linear;
    }

    .home-container::after {
        top: auto;
        bottom: -150px;
        left: auto;
        right: -150px;
        background: radial-gradient(circle, rgba(0, 123, 255, 0.1), rgba(0, 123, 255, 0) 70%);
        animation: rotate 10s infinite linear reverse;
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

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div class="home-container">
    <h2>Welcome to the University of Messina Student Management System</h2>
    <p>This system allows administrators, professors, and students to manage courses, exams, and grades effectively. Streamline your academic journey with our comprehensive tools and features designed to enhance your university experience.</p>
    <div class="button-container">
        <a href="<?php echo BASE_URL; ?>?url=auth/login">Login</a>
    </div>
</div>

<?php include '../app/views/templates/footer.php'; ?>
