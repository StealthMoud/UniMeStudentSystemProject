<!DOCTYPE html>
<html>
<head>
    <title>Choose Courses</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-out;
        }

        .container h1 {
            text-align: center;
            color: #4a90e2;
            margin-bottom: 40px;
            font-size: 32px;
            font-weight: 600;
            animation: fadeInDown 0.8s ease-out;
        }

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
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }

        .form-container button:hover {
            background-color: #218838;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        h2 {
            text-align: center;
            color: #4a90e2;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            animation: fadeInDown 0.8s ease-out;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            background-color: #e9ecef;
            margin: 10px 0;
            padding: 15px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        ul li:hover {
            background-color: #d6e0f0;
        }

        .success-message {
            color: green;
            text-align: center;
            font-size: 1.2em;
            margin: 20px 0;
            animation: fadeIn 0.8s ease-out;
        }

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
            animation: pulse 2s infinite;
            text-align: center;
        }

        .back-button:hover {
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

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Choose Courses</h1>

    <?php if (isset($data['success'])): ?>
        <p class="success-message"><?php echo htmlspecialchars($data['success']); ?></p>
    <?php endif; ?>

    <div class="form-container">
        <form method="post" action="<?php echo BASE_URL; ?>?url=professor/chooseCourses">
            <label for="major">Select Major:</label>
            <select name="major_id" id="major" onchange="this.form.submit()">
                <option value="">Select Major</option>
                <optgroup label="Bachelor Majors">
                    <?php foreach ($data['bachelorMajors'] as $major): ?>
                        <option value="<?php echo $major['id']; ?>" <?php if (isset($_POST['major_id']) && $_POST['major_id'] == $major['id']) echo 'selected'; ?>><?php echo htmlspecialchars($major['name'] ?? ''); ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="Master Majors">
                    <?php foreach ($data['masterMajors'] as $major): ?>
                        <option value="<?php echo $major['id']; ?>" <?php if (isset($_POST['major_id']) && $_POST['major_id'] == $major['id']) echo 'selected'; ?>><?php echo htmlspecialchars($major['name'] ?? ''); ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </form>
    </div>

    <?php if (!empty($data['courses'])): ?>
        <div class="form-container">
            <form method="post" action="<?php echo BASE_URL; ?>?url=professor/chooseCourses">
                <input type="hidden" name="major_id" value="<?php echo htmlspecialchars($_POST['major_id']); ?>">
                <h2>Courses</h2>
                <ul>
                    <?php foreach ($data['courses'] as $course): ?>
                        <li>
                            <input type="checkbox" name="selected_courses[]" value="<?php echo $course['id']; ?>">
                            <?php echo htmlspecialchars($course['name'] ?? ''); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="submit">Assign Selected Courses</button>
            </form>
        </div>
    <?php endif; ?>

    <h2>Your Course Requests</h2>
    <?php if (!empty($data['requests'])): ?>
        <table>
            <thead>
            <tr>
                <th>Course</th>
                <th>Status</th>
                <th>Requested On</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['requests'] as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                    <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No course requests found.</p>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>?url=professor/dashboard" class="back-button">Back to Professor Dashboard</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageElement = document.querySelector('.success-message');
        if (messageElement) {
            setTimeout(function() {
                messageElement.style.opacity = '0';
                setTimeout(function() {
                    messageElement.remove();
                }, 1000);
            }, 5000);
        }
    });
</script>
</body>
</html>
