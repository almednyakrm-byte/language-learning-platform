**edit_تدريبات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/تدريبات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if ($data) {
    // Set data in session
    $_SESSION['edit_data'] = $data;
} else {
    // Redirect to list page if data does not exist
    header('Location: list_تدريبات.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit تدريبات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-teal-500 {
            background-color: #0097a7;
        }
        .text-gray-500 {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit تدريبات</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-500 mb-2">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded" value="<?php echo $_SESSION['edit_data']['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-500 mb-2">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-300 rounded"><?php echo $_SESSION['edit_data']['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/تدريبات.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_تدريبات.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/تدريبات.php**

<?php
// Check if id exists in URL
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch existing record details
    $stmt = $conn->prepare('SELECT * FROM تدريبات WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($data);
    exit;
}

// Check if data exists in session
if (isset($_SESSION['edit_data'])) {
    // Update existing record
    $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare('UPDATE تدريبات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success message
    echo 'success';
    exit;
}
?>