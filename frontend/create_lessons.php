<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Module slug
$mod_slug = 'lessons';

// Page title
$page_title = 'Create Lessons';

// Include header
include 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl text-blue-500 font-bold mb-4">Create Lessons</h1>
    <form id="create-lessons-form">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" id="date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
            <input type="time" id="time" name="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
        </div>
        <div class="mb-4">
            <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
            <input type="number" id="duration" name="duration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
        </div>
        <button type="submit" class="py-2 px-4 bg-orange-300 text-white font-semibold rounded-md shadow-sm hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">Create Lessons</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-lessons-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/lessons.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug; ?>.php';
                }
            });
        });
    });
</script>