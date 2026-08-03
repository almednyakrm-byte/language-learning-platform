**edit_كلمات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from the URL
$id = $_GET['id'];

// Fetch the existing record details
$data = json_decode(file_get_contents('../backend/كلمات.php?id=' . $id), true);

// Check if the record exists
if (empty($data)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit كلمات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-teal-500 mb-4">Edit كلمات</h1>
        <form id="edit-form" class="bg-gray-100 p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-gray-500 font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" class="block w-full p-2 border border-gray-400 rounded" value="<?= $data['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-500 font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-400 rounded"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
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
                    url: '../backend/كلمات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/كلمات.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get the record details from the database
// Replace this with your actual database query
$data = array(
    'id' => $_GET['id'],
    'title' => 'Example Title',
    'description' => 'Example Description'
);

echo json_encode($data);