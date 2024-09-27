</main>
<footer>
    <style>
        /* Footer styling */
        footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            text-align: center;
            width: 100%;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: fadeInUp 1s ease-out;
            overflow: hidden;
        }

        .footer-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        footer p {
            margin: 10px 0;
            font-size: 1rem;
            color: #dddddd;
            animation: fadeInUp 1.5s ease-out;
        }

        .social-icons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            animation: fadeInUp 2s ease-out;
        }

        .social-icons a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            padding: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .social-icons a:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .social-icons a i {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Background animation */
        footer::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1), transparent);
            animation: spin 10s linear infinite;
            z-index: 0;
            opacity: 0.3;
        }

        /* Keyframe animations */
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
            .footer-content {
                text-align: center;
                padding: 0 15px;
            }

            .social-icons {
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .social-icons a {
                font-size: 1.2rem;
                padding: 10px;
            }
        }
    </style>
    <div class="footer-content">
        <p>&copy; 2024 University of Messina. All Rights Reserved.</p>
        <p>Created by Sayed Mahmoud Mohseni, a second-year Data Analysis student, as part of a university assignment.</p>
        <div class="social-icons">
            <a href="https://github.com/StealthMoud" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>
            <a href="https://www.linkedin.com/in/sayed-mahmoud-mohseni-7299b72a1/" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</footer>
</body>
</html>
