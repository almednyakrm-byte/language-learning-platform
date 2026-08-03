**edit_معلمين.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/معلمين.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set form fields
$name = $data['name'];
$description = $data['description'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit معلمين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">Edit معلمين</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-slate-300 rounded-md" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-slate-300 rounded-md" rows="4"><?= $description ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/معلمين.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/معلمين.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'ID not set.'));
    exit;
}

// Get the ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('your_host', 'your_username', 'your_password', 'your_database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Connection failed: ' . $conn->connect_error));
    exit;
}

// Query to fetch existing record details
$query = "SELECT * FROM معلمين WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array('success' => false, 'message' => 'Record not found.'));
}

// Close connection
$conn->close();
?>


**backend/edit.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'ID not set.'));
    exit;
}

// Get the ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('your_host', 'your_username', 'your_password', 'your_database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Connection failed: ' . $conn->connect_error));
    exit;
}

// Get the form data
$name = $_POST['name'];
$description = $_POST['description'];

// Query to update existing record
$query = "UPDATE معلمين SET name = '$name', description = '$description' WHERE id = '$id'";
$result = $conn->query($query);

// Check if update was successful
if ($result) {
    echo json_encode(array('success' => true, 'message' => 'Record updated successfully.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error updating record: ' . $conn->error));
}

// Close connection
$conn->close();
?>