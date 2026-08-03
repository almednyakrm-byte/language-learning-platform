**create_تدريبات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-lg p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold text-gray-500">إضافة تدريب جديد</h2>
        <form id="create-form" class="mt-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-bold text-gray-500">اسم التدريب</label>
                    <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-teal-500 focus:border-teal-500">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-bold text-gray-500">وصف التدريب</label>
                    <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-teal-500 focus:border-teal-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="duration" class="block text-sm font-bold text-gray-500">مدة التدريب</label>
                    <input type="text" id="duration" name="duration" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-teal-500 focus:border-teal-500">
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-bold text-gray-500">سعر التدريب</label>
                    <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة تدريب</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/تدريبات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_تدريبات.php';
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
include 'footer.php';
?>


**Note:** Make sure to replace `header.php`, `navigation.php`, and `footer.php` with your actual header, navigation, and footer files. Also, update the `../backend/تدريبات.php` URL to match your actual backend file.