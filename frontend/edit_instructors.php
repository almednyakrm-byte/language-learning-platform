<?php
// edit_instructors.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_instructors.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 mb-4">Edit Instructor</h2>
        <form id="edit-instructor-form">
            <div class="mb-4">
                <label for="name" class="block text-blue-500 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-blue-500 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-blue-500 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/instructors.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                }
            });

            $('#edit-instructor-form').submit(function(e) {
                e.preventDefault();
                var formData = {
                    'id': id,
                    'name': $('#name').val(),
                    'email': $('#email').val(),
                    'phone': $('#phone').val()
                };
                $.ajax({
                    type: 'PUT',
                    url: '../backend/instructors.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_instructors.php';
                    }
                });
            });
        });
    </script>
</body>
</html>