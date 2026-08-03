**edit_لغات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/لغات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل لغة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل لغة</h2>
        <form id="edit-language-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">الاسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" rows="4"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-language-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/لغات.php',
                    data: $(this).serialize() + '&id=' + <?= $id ?>,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**لغات.php (backend)**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = array();
$query = "SELECT * FROM لغات WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if ($result) {
    $record = mysqli_fetch_assoc($result);
}

// Update record
if (isset($_POST['name']) && isset($_POST['description'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $query = "UPDATE لغات SET name = '$name', description = '$description' WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'Error updating record';
    }
}

// Close database connection
mysqli_close($conn);
?>


Note: This code assumes you have a database connection established in `لغات.php` and that the `لغات` table has columns for `id`, `name`, and `description`. You may need to modify the code to fit your specific database schema. Additionally, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.