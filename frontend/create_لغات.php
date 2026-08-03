**create_لغات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO لغات (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect back to list page
        header('Location: list_لغات.php');
        exit;
    } else {
        echo 'Error inserting data';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create Languages Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Languages</h2>
    <form id="create-languages-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-languages-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/لغات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_لغات.php';
            } else {
                alert('Error creating languages');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>


**backend/لغات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $query = "INSERT INTO لغات (name, description) VALUES ('".$_POST['name']."', '".$_POST['description']."')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false));
    }
}