<?php
// edit_lessons.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_lessons.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lessons</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-500 mb-4">Edit Lessons</h2>
        <form id="edit-lessons-form">
            <div class="mb-4">
                <label for="title" class="block text-blue-500 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-blue-500 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-lessons-form');

        // Fetch existing record details
        fetch(`../backend/lessons.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/lessons.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    title: formData.get('title'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_lessons.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>