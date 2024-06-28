<?php include '../app/views/templates/header.php'; ?>

<style>
    .container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 40px;
        background-color: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.5s ease-out;
    }

    .container h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 20px;
        font-size: 32px;
        font-weight: 600;
    }

    .message, .error, .confirm {
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.2rem;
        padding: 10px;
        border-radius: 6px;
    }

    .message {
        color: #28a745;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }

    .error {
        color: #dc3545;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .confirm {
        color: #856404;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        padding: 15px;
        font-size: 1.1rem;
        border-radius: 10px;
        text-align: center;
        margin-top: 20px;
    }

    .confirm form {
        margin-top: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
        font-size: 0.9rem;
    }

    th, td {
        padding: 12px;
        border: 1px solid #dee2e6;
        text-align: left;
        vertical-align: top;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    .details-section {
        padding: 10px;
        background-color: #f1f1f1;
        border-radius: 6px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .info-item {
        flex: 1 1 calc(50% - 20px);
        margin-bottom: 10px;
    }

    .info-item strong {
        font-weight: bold;
        display: block;
    }

    .documents-list {
        list-style-type: none;
        padding-left: 0;
        margin: 0;
    }

    .documents-list li {
        margin-bottom: 10px;
    }

    .document-link {
        color: #007bff;
        text-decoration: none;
        display: block;
        margin-bottom: 5px;
    }

    .document-link:hover {
        text-decoration: underline;
    }

    .document-description {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 10px;
    }

    .actions button {
        margin-bottom: 5px;
    }

    .approve-btn {
        background-color: #28a745;
    }

    .approve-btn:hover {
        background-color: #218838;
    }

    .reject-btn {
        background-color: #dc3545;
    }

    .reject-btn:hover {
        background-color: #c82333;
    }

    .disabled-btn {
        background-color: #6c757d;
        cursor: not-allowed;
    }

    .actions form {
        display: inline;
    }

    .actions button {
        padding: 8px 12px;
        font-size: 1rem;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .back-button {
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

    .back-button:hover {
        background-color: #5a6268;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Style for confirmation button in the confirm message */
    .confirm .approve-btn {
        padding: 10px 20px;
        font-size: 1.1rem;
        background-color: #007bff;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .confirm .approve-btn:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* New styles for confirm dialog */
    #confirmDialog {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-radius: 8px;
    }

    #confirmDialog .dialog-content {
        margin-bottom: 20px;
    }

    #confirmDialog .dialog-buttons {
        text-align: right;
    }

    #confirmDialog .dialog-buttons button {
        padding: 10px 20px;
        margin-left: 10px;
        font-size: 1rem;
        border-radius: 6px;
        cursor: pointer;
    }

    #confirmDialog .approve-btn {
        background-color: #28a745;
        color: white;
    }

    #confirmDialog .approve-btn:hover {
        background-color: #218838;
    }

    #confirmDialog .cancel-btn {
        background-color: #dc3545;
        color: white;
    }

    #confirmDialog .cancel-btn:hover {
        background-color: #c82333;
    }
</style>

<?php
// Initialize session and retrieve messages
use App\utilities\Session;
Session::init();
$message = Session::flash('message');
$error = Session::flash('error');
$confirmMessage = Session::flash('confirm_message');
?>

<div class="container">
    <h2>Enrollment Requests</h2>

    <?php if ($message): ?>
        <p class="message" id="flash-message"><?php echo htmlspecialchars($message ?? ''); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error" id="flash-error"><?php echo htmlspecialchars($error ?? ''); ?></p>
    <?php endif; ?>

    <?php if ($confirmMessage): ?>
        <div class="confirm" id="flash-confirm"><?php echo htmlspecialchars($confirmMessage ?? ''); ?></div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Applicant Username</th>
            <th>Details</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['enrollmentRequests'])): ?>
            <?php foreach ($data['enrollmentRequests'] as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($request['username'] ?? ''); ?></td>
                    <td>
                        <div class="details-section">
                            <?php
                            $info = json_decode($request['additional_info'], true);
                            if (!empty($info)) {
                                foreach ($info as $key => $value):
                                    // Show major name instead of major id
                                    if ($key == 'major') {
                                        echo "<div class='info-item'><strong>Major:</strong> " . htmlspecialchars($request['major_name'] ?? '') . "</div>";
                                    } else {
                                        echo "<div class='info-item'><strong>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $key)) ?? '') . ":</strong> " . htmlspecialchars($value ?? '') . "</div>";
                                    }
                                endforeach;
                            } else {
                                echo "<div class='info-item' style='flex: 1 1 100%;'><strong>No additional info provided.</strong></div>";
                            }
                            ?>
                            <?php if (!empty($request['documents'])): ?>
                                <div class="info-item" style="flex: 1 1 100%;">
                                    <strong>Documents:</strong>
                                    <ul class="documents-list">
                                        <?php foreach ($request['documents'] as $doc): ?>
                                            <li>
                                                <a href="<?php echo htmlspecialchars(BASE_URL . '/' . ($doc['path'] ?? '')); ?>" target="_blank" class="document-link">
                                                    <?php echo htmlspecialchars($doc['name'] ?? ''); ?>
                                                </a>
                                                <div class="document-description">
                                                    <?php echo htmlspecialchars($doc['description'] ?? ''); ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="info-item" style="flex: 1 1 100%;"><strong>Documents:</strong> No documents</div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars(ucfirst($request['application_status']) ?? ''); ?></td>
                    <td class="actions">
                        <?php if ($request['application_status'] === 'pending'): ?>
                            <form action="<?php echo BASE_URL; ?>?url=admin/approveEnrollmentRequest" method="POST">
                                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id'] ?? ''); ?>">
                                <button type="submit" class="approve-btn">Approve</button>
                            </form>
                            <form action="<?php echo BASE_URL; ?>?url=admin/rejectEnrollmentRequest" method="POST">
                                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id'] ?? ''); ?>">
                                <button type="submit" class="reject-btn">Reject</button>
                            </form>
                        <?php elseif ($request['application_status'] === 'rejected'): ?>
                            <form action="<?php echo BASE_URL; ?>?url=admin/approveEnrollmentRequest" method="POST" class="confirm-form">
                                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id'] ?? ''); ?>">
                                <button type="button" class="approve-btn" onclick="showConfirmDialog(this)">Approve</button>
                            </form>
                            <button class="disabled-btn" disabled>Rejected</button>
                        <?php elseif ($request['application_status'] === 'approved'): ?>
                            <button class="disabled-btn" disabled>Approved</button>
                        <?php else: ?>
                            <button class="disabled-btn" disabled><?php echo htmlspecialchars(ucfirst($request['application_status']) ?? ''); ?></button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">No enrollment requests found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="<?php echo BASE_URL; ?>?url=admin/dashboard" class="back-button">Back to Admin Dashboard</a>
</div>

<!-- Confirmation Dialog -->
<div id="confirmDialog">
    <div class="dialog-content">
        <p>This request was previously rejected. Are you sure you want to approve it now?</p>
    </div>
    <div class="dialog-buttons">
        <button class="approve-btn" onclick="confirmApproval()">Confirm Approval</button>
        <button class="cancel-btn" onclick="hideConfirmDialog()">Cancel</button>
    </div>
</div>

<script>
    // JavaScript to hide messages after 5 seconds
    setTimeout(function() {
        const flashMessage = document.getElementById('flash-message');
        const flashError = document.getElementById('flash-error');

        if (flashMessage) flashMessage.style.display = 'none';
        if (flashError) flashError.style.display = 'none';
    }, 5000); // 5000 milliseconds = 5 seconds

    // JavaScript to show and hide the custom confirmation dialog
    function showConfirmDialog(button) {
        const dialog = document.getElementById('confirmDialog');
        dialog.style.display = 'block';

        // Attach form data to confirmation button
        const form = button.closest('form');
        dialog.dataset.requestId = form.querySelector('input[name="request_id"]').value;
    }

    function hideConfirmDialog() {
        document.getElementById('confirmDialog').style.display = 'none';
    }

    function confirmApproval() {
        const dialog = document.getElementById('confirmDialog');
        const requestId = dialog.dataset.requestId;

        // Create a form to submit the approval request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>?url=admin/approveEnrollmentRequest';

        // Add hidden input fields to the form
        const requestIdInput = document.createElement('input');
        requestIdInput.type = 'hidden';
        requestIdInput.name = 'request_id';
        requestIdInput.value = requestId;

        const confirmApprovalInput = document.createElement('input');
        confirmApprovalInput.type = 'hidden';
        confirmApprovalInput.name = 'confirm_approval';
        confirmApprovalInput.value = 'yes';

        form.appendChild(requestIdInput);
        form.appendChild(confirmApprovalInput);

        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    }
</script>

<?php include '../app/views/templates/footer.php'; ?>
