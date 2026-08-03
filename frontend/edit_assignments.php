<?php
// edit_assignments.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_assignments.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-500 mb-4">Edit Assignment</h2>
        <form id="edit-assignment-form">
            <div class="mb-4">
                <label for="title" class="block text-blue-500 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-blue-500 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300"></textarea>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-blue-500 text-sm font-bold mb-2">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded-lg">Update Assignment</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/assignments.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#title').val(data.title);
                    $('#description').val(data.description);
                    $('#due_date').val(data.due_date);
                }
            });

            $('#edit-assignment-form').submit(function(e) {
                e.preventDefault();
                const formData = {
                    title: $('#title').val(),
                    description: $('#description').val(),
                    due_date: $('#due_date').val()
                };
                $.ajax({
                    type: 'PUT',
                    url: '../backend/assignments.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function() {
                        window.location.href = 'list_assignments.php';
                    }
                });
            });
        });
    </script>
</body>
</html>