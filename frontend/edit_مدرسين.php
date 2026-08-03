**edit_مدرسين.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/مدرسين.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مدرس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }
        .slate-900 {
            color: #1a1d23;
        }
        .indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 mt-12">
        <h1 class="text-3xl font-bold slate-900 mb-4">تعديل مدرس</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">اسم المدرس</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-slate-500" value="<?php echo $existingRecord['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-700">بريد إلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-slate-500" value="<?php echo $existingRecord['email']; ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-700">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-slate-500" value="<?php echo $existingRecord['phone']; ?>">
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">حفظ</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مدرسين.php',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'تم الحفظ',
                            text: 'تم تعديل بيانات المدرس بنجاح',
                            icon: 'success',
                            confirmButtonText: 'حسناً'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'list_مدرسين.php';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>

**مدرسين.php (backend)**

<?php
// Check if ID is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get existing record details
    $id = $_GET['id'];
    $sql = "SELECT * FROM مدرسين WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array());
    }

    // Close database connection
    $conn->close();
}
?>

Note: This code assumes that you have a `مدرسين` table in your database with columns `id`, `name`, `email`, and `phone`. You should replace the placeholders with your actual database credentials and table structure.