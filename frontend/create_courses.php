<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Define module slug
$mod_slug = 'courses';

// Define page title
$page_title = 'Create Course';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md mt-10">
        <h2 class="text-3xl text-blue-500 font-bold mb-4">Create Course</h2>
        <form id="create-course-form">
            <div class="mb-4">
                <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                <input type="text" id="course_name" name="course_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
                <input type="text" id="course_code" name="course_code" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="course_description" class="block text-sm font-medium text-gray-700">Course Description</label>
                <textarea id="course_description" name="course_description" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="mb-4">
                <label for="course_duration" class="block text-sm font-medium text-gray-700">Course Duration</label>
                <input type="number" id="course_duration" name="course_duration" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="course_fee" class="block text-sm font-medium text-gray-700">Course Fee</label>
                <input type="number" id="course_fee" name="course_fee" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-orange-300 hover:bg-orange-400 text-white font-bold rounded">Create Course</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-course-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/courses.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>