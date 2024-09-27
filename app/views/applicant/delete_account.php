<?php include '../app/views/templates/header.php'; ?>

<style>
    .container {
        max-width: 600px;
        margin: 60px auto;
        padding: 40px;
        background-color: #ffe0e0; /* Light red background */
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 0.5s ease-out;
        position: relative; /* Ensure container has a position for stacking context */
        z-index: 1; /* Set lower than the modal */
    }

    .container h2 {
        text-align: center;
        color: #d32f2f; /* Dark red */
        margin-bottom: 40px;
        font-size: 28px;
        font-weight: 600;
        animation: fadeInDown 0.5s ease-out;
    }

    .container p {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 30px;
        text-align: center;
    }

    .container form {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .container button,
    .container .cancel-button {
        padding: 12px 20px;
        font-size: 1rem;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .container button {
        background-color: #d32f2f;
    }

    .container button:hover {
        background-color: #b71c1c;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .container .cancel-button {
        background-color: #6c757d;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .container .cancel-button:hover {
        background-color: #5a6268;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000; /* Ensure the modal is on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6); /* Dark overlay */
        padding-top: 60px;
        animation: fadeIn 0.5s ease-out;
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 30px;
        border: none;
        width: 80%;
        max-width: 500px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        text-align: center;
        animation: slideIn 0.5s ease-out;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #333;
    }

    .modal h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #d32f2f;
    }

    .modal p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 20px;
    }

    .modal-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .modal-buttons button {
        padding: 12px 20px;
        font-size: 1rem;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modal-buttons .confirm-button {
        background-color: #d32f2f;
    }

    .modal-buttons .confirm-button:hover {
        background-color: #b71c1c;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-buttons .cancel-button {
        background-color: #6c757d;
    }

    .modal-buttons .cancel-button:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
        }
        to {
            transform: translateY(0);
        }
    }
</style>

<!-- The Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal()">&times;</span>
        <h2>Confirm Account Deletion</h2>
        <p>Are you absolutely sure you want to delete your account? This action cannot be undone.</p>
        <div class="modal-buttons">
            <button class="confirm-button" onclick="submitDeleteForm()">Confirm</button>
            <button class="cancel-button" onclick="hideModal()">Cancel</button>
        </div>
    </div>
</div>

<div class="container">
    <h2>Delete Account Confirmation</h2>
    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
    <form id="deleteAccountForm" action="<?php echo BASE_URL; ?>?url=applicant/deleteAccount" method="POST">
        <button type="button" onclick="showModal()">Delete Account</button>
        <a href="<?php echo BASE_URL; ?>?url=applicant/dashboard" class="cancel-button">Cancel</a>
    </form>
</div>

<script>
    function showModal() {
        document.getElementById('confirmationModal').style.display = 'block';
    }

    function hideModal() {
        document.getElementById('confirmationModal').style.display = 'none';
    }

    function submitDeleteForm() {
        document.getElementById('deleteAccountForm').submit();
    }
</script>

<?php include '../app/views/templates/footer.php'; ?>
