<?php
// edit_quizzes.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_quizzes.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 mb-4">Edit Quiz</h2>
        <form id="edit-quiz-form">
            <div class="mb-4">
                <label for="title" class="block text-blue-500 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="bg-gray-100 border border-gray-300 text-blue-500 rounded-lg focus:ring-orange-300 focus:border-orange-300 block w-full p-2.5">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-blue-500 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="bg-gray-100 border border-gray-300 text-blue-500 rounded-lg focus:ring-orange-300 focus:border-orange-300 block w-full p-2.5 h-32"></textarea>
            </div>
            <button type="submit" class="text-white bg-orange-300 hover:bg-orange-400 focus:ring-4 focus:outline-none focus:ring-blue-500 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Quiz</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-quiz-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/quizzes.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/quizzes.php', {
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
                    window.location.href = 'list_quizzes.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>