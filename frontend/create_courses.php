<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'courses';

// Page title
$page_title = 'Create Course';

// Include header
include 'header.php';
?>

<!-- Main content -->
<main class="flex-1 overflow-x-hidden overflow-y-auto">
    <div class="container mx-auto px-6 py-8">
        <h3 class="text-2xl font-bold mb-4 text-blue-500"><?= $page_title ?></h3>
        <form id="create-course-form">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="course_name">
                        Course Name
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="course_name" type="text" placeholder="Course Name">
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="course_code">
                        Course Code
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="course_code" type="text" placeholder="Course Code">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="course_description">
                        Course Description
                    </label>
                    <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="course_description" placeholder="Course Description"></textarea>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="course_duration">
                        Course Duration
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="course_duration" type="text" placeholder="Course Duration">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="course_fee">
                        Course Fee
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="course_fee" type="number" placeholder="Course Fee">
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="course_status">
                        Course Status
                    </label>
                    <select class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="course_status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <button class="bg-blue-500 hover:bg-orange-300 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Create Course
            </button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            var formData = {
                'course_name': $('#course_name').val(),
                'course_code': $('#course_code').val(),
                'course_description': $('#course_description').val(),
                'course_duration': $('#course_duration').val(),
                'course_fee': $('#course_fee').val(),
                'course_status': $('#course_status').val()
            };
            $.ajax({
                type: 'POST',
                url: '../backend/courses.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>