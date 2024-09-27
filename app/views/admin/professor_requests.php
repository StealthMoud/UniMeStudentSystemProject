<!DOCTYPE html>
<html>
<head>
    <title>Professor Course Requests</title>
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

        h1 {
            text-align: center;
            color: #4a90e2;
            margin-bottom: 40px;
            font-size: 32px;
            font-weight: 600;
            animation: fadeInDown 0.8s ease-out;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out;
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

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .button-approve {
            background-color: #28a745;
            color: white;
        }

        .button-reject {
            background-color: #dc3545;
            color: white;
        }

        .button-approve:hover {
            background-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .button-reject:hover {
            background-color: #c82333;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .back-button {
            display: inline-block;
            padding: 12px 25px;
            margin: 20px auto;
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
    </style>
</head>
<body>
<div class="container">
    <h1>Professor Course Requests</h1>

    <?php if (isset($data['requests']) && !empty($data['requests'])): ?>
        <table>
            <thead>
            <tr>
                <th>Course</th>
                <th>Professor</th>
                <th>Status</th>
                <th>Requested On</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['requests'] as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['professor_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['status'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                    <td>
                        <?php if ($request['status'] === 'pending'): ?>
                            <form method="post" action="<?php echo BASE_URL; ?>?url=admin/updateRequestStatus">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <button type="submit" name="status" value="approved" class="button button-approve">Approve</button>
                                <button type="submit" name="status" value="rejected" class="button button-reject">Reject</button>
                            </form>
                        <?php else: ?>
                            <?php echo htmlspecialchars($request['status'] ?? ''); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No requests found.</p>
    <?php endif; ?>
    <a href="<?php echo BASE_URL; ?>?url=admin/dashboard" class="back-button">Back to Admin Dashboard</a>
</div>
</body>
</html>
