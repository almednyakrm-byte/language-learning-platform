**create_كلمات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <h1 class="text-3xl font-bold text-gray-900">Create كلمات</h1>

    <form id="create-kelam" class="max-w-md mx-auto mt-6 bg-white rounded-lg shadow-md p-8">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
            <select id="category" name="category" class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                <option value="">Select Category</option>
                <!-- Add options here -->
            </select>
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-kelam').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '../backend/كلمات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_كلمات.php';
                    } else {
                        alert('Error creating كلمات');
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


**backend/كلمات.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO كلمات (name, description, category) VALUES ('$name', '$description', '$category')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating كلمات';
    }
} else {
    echo 'Invalid request';
}
?>


**Note:** This code assumes you have a MySQL database connection established in the `backend/كلمات.php` file. You should replace the placeholder values with your actual database credentials and table names. Additionally, this code does not include any error handling or security measures, such as input validation and sanitization, which you should implement in a production environment.