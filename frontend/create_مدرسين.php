**create_مدرسين.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $errors = array();
    if (empty($_POST['name'])) {
        $errors[] = 'Name is required';
    }
    if (empty($_POST['email'])) {
        $errors[] = 'Email is required';
    }
    if (empty($_POST['phone'])) {
        $errors[] = 'Phone is required';
    }
    if (empty($_POST['address'])) {
        $errors[] = 'Address is required';
    }

    if (empty($errors)) {
        // Insert data into database
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $sql = "INSERT INTO مدرسين (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_مدرسين.php');
            exit;
        } else {
            $errors[] = 'Error inserting data';
        }
    }
}

// Include header
require_once '../includes/header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Create مدرسين form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create مدرسين</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address:</label>
            <textarea id="address" name="address" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<!-- AJAX script -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مدرسين.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مدرسين.php';
                    } else {
                        alert('Error creating record');
                    }
                }
            });
        });
    });
</script>

**Note:** This code assumes that you have a `config/database.php` file that contains your database connection settings, and a `backend/مدرسين.php` file that handles the form submission and inserts the data into the database. You will need to create these files and modify the code to match your specific database schema and backend logic.