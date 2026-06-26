**create_لغات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
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

// Include header and navigation
require_once '../includes/header.php';
?>

<!-- Create Languages Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New Language</h2>
    <form id="create-language-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create Language</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_لغات.js**
javascript
// Get form element
const form = document.getElementById('create-language-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to server
    fetch('../backend/لغات.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_لغات.php';
        } else {
            console.error(data.error);
        }
    })
    .catch((error) => console.error(error));
});


**لغات.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $query = "INSERT INTO لغات (name, description) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $_POST['name'], $_POST['description']);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error inserting data']);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Invalid form data']);
}
?>