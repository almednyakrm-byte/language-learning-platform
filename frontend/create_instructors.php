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
$mod_slug = 'instructors';

// Define page title
$page_title = 'Create Instructor';

// Include header
require_once 'header.php';
?>

<!-- Premium Tailwind UI form -->
<div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-blue-500 mb-4">Create Instructor</h2>
    <form id="create-instructor-form">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <select id="department" name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">Select Department</option>
                    <option value="Math">Math</option>
                    <option value="Science">Science</option>
                    <option value="English">English</option>
                </select>
            </div>
            <div>
                <label for="designation" class="block text-sm font-medium text-gray-700">Designation</label>
                <input type="text" id="designation" name="designation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
        </div>
        <button type="submit" class="mt-4 py-2 px-4 bg-orange-300 text-white rounded-md hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-300">Create Instructor</button>
    </form>
</div>

<!-- AJAX JavaScript to POST form data -->
<script>
    $(document).ready(function() {
        $('#create-instructor-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/instructors.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>