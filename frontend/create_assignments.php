<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Define module slug
$mod_slug = 'assignments';

// Define page title
$page_title = 'Create Assignment';

// Include header
require_once 'header.php';
?>

<!-- Main content -->
<main class="flex-1 overflow-x-hidden overflow-y-auto">
    <div class="container mx-auto px-6 py-8">
        <h3 class="text-2xl font-bold text-blue-500 mb-4"><?= $page_title ?></h3>
        <form id="create-assignment-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-blue-500 text-sm font-bold mb-2" for="title">Title</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" placeholder="Assignment Title">
            </div>
            <div class="mb-4">
                <label class="block text-blue-500 text-sm font-bold mb-2" for="description">Description</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" placeholder="Assignment Description"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-blue-500 text-sm font-bold mb-2" for="due_date">Due Date</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="due_date" type="date" placeholder="YYYY-MM-DD">
            </div>
            <div class="mb-4">
                <label class="block text-blue-500 text-sm font-bold mb-2" for="points">Points</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="points" type="number" placeholder="Assignment Points">
            </div>
            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Create Assignment</button>
        </form>
    </div>
</main>

<!-- Include footer -->
<?php require_once 'footer.php'; ?>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-assignment-form').submit(function(e) {
            e.preventDefault();
            var formData = {
                title: $('#title').val(),
                description: $('#description').val(),
                due_date: $('#due_date').val(),
                points: $('#points').val()
            };
            $.ajax({
                type: 'POST',
                url: '../backend/assignments.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>