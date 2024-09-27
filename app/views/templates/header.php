<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>University of Messina</title>
    <style>
        /* Basic reset and typography */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            line-height: 1.6;
            color: #333;
        }

        /* Header container styling */
        header {
            background: linear-gradient(135deg, #007bff, #6610f2);
            padding: 20px 0;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            animation: slideInDown 1s ease-out;
        }

        /* Header titles */
        header h1 {
            margin: 0;
            font-size: 2.5rem;
            animation: fadeIn 1.2s ease-out;
        }

        header h2 {
            margin: 5px 0 20px;
            font-size: 1.5rem;
            font-weight: 300;
            color: #dddddd;
            animation: fadeIn 1.4s ease-out;
        }

        /* Navigation styling */
        header nav ul {
            display: flex;
            justify-content: center;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 20px 0 0;
        }

        header nav ul li {
            margin: 0 15px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        header nav ul li a i {
            font-size: 1.2rem;
        }

        header nav ul li a::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) scale(0);
            transition: transform 0.3s ease;
            z-index: 0;
        }

        header nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        header nav ul li a:hover::before {
            transform: translateY(-50%) scale(2);
        }

        /* Background animation */
        header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1), transparent);
            animation: spin 8s linear infinite;
            z-index: 0;
            opacity: 0.3;
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

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }

            header h2 {
                font-size: 1.2rem;
            }

            header nav ul {
                flex-direction: column;
                margin: 15px 0 0;
            }

            header nav ul li {
                margin: 10px 0;
            }

            header nav ul li a {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <h1>University of Messina</h1>
        <h2>Student Management System</h2>
        <nav>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>?url="><i class="fas fa-home"></i> Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>?url=home/about"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="<?php echo BASE_URL; ?>?url=home/contact"><i class="fas fa-envelope"></i> Contact</a></li>
                <li><a href="<?php echo BASE_URL; ?>?url=auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
